<?php
// +----------------------------------------------------------------------
// | iboxsPHP [ WE CAN DO IT JUST iboxs IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2025 http://iboxsphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------
namespace iboxs\console\command;

use DirectoryIterator;
use iboxs\console\Command;
use iboxs\console\Input;
use iboxs\console\input\Argument;
use iboxs\console\input\Option;
use iboxs\console\Output;
use iboxs\console\Table;
use iboxs\event\RouteLoaded;

class RouteList extends Command
{
    protected $sortBy = [
        'rule'   => 0,
        'route'  => 1,
        'method' => 2,
        'name'   => 3,
        'domain' => 4,
    ];

    protected function configure()
    {
        $this->setName('route:list')
            ->addArgument('style', Argument::OPTIONAL, "the style of the table.", 'default')
            ->addOption('sort', 's', Option::VALUE_OPTIONAL, 'order by rule name.', 0)
            ->addOption('more', 'm', Option::VALUE_NONE, 'show route options.')
            ->setDescription('show route list.');
    }

    protected function execute(Input $input, Output $output)
    {
        $filename = $this->app->getRootPath() . 'runtime' . DIRECTORY_SEPARATOR . 'route_list.php';

        if (is_file($filename)) {
            unlink($filename);
        } elseif (!is_dir(dirname($filename))) {
            mkdir(dirname($filename), 0755);
        }

        $content = $this->getRouteList();
        file_put_contents($filename, 'Route List' . PHP_EOL . $content);
    }

    protected function scanRoute($path, $root, $autoGroup)
    {
        $iterator = new DirectoryIterator($path);
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isDot()) {
                continue;
            }

            if ($fileinfo->getType() == 'file' && $fileinfo->getExtension() == 'php') {
                $groupName = str_replace('\\', '/', substr_replace($fileinfo->getPath(), '', 0, strlen($root)));
                if ($groupName) {
                    $this->app->route->group($groupName, function()  use ($fileinfo) {
                        include $fileinfo->getRealPath();
                    });
                } else {
                    include $fileinfo->getRealPath();
                }
            } elseif ($autoGroup && $fileinfo->isDir()) {
                $this->scanRoute($fileinfo->getPathname(), $root, $autoGroup);
            }
        }
    }

    protected function getRouteList(?string $dir = null): string
    {
        $this->app->route->clear();
        $this->app->route->lazy(false);
        $autoGroup = $this->app->route->config('route_auto_group');
        $path      = $this->app->getRootPath() . 'route' . DIRECTORY_SEPARATOR;

        $this->scanRoute($path, $path, $autoGroup);

        //触发路由载入完成事件
        $this->app->event->trigger(RouteLoaded::class);

        $table = new Table();

        if ($this->input->hasOption('more')) {
            $header = ['Rule', 'Route', 'Method', 'Name', 'Domain', 'Option', 'Pattern'];
        } else {
            $header = ['Rule', 'Route', 'Method', 'Name'];
        }

        $table->setHeader($header);

        $routeList = $this->app->route->getRuleList();
        $rows      = [];

        foreach ($routeList as $item) {
            if (is_array($item['route'])) {
                $item['route'] = '[' . $item['route'][0] .' , ' . $item['route'][1] . ']';
            } else {
                $item['route'] = $item['route'] instanceof \Closure ? '<Closure>' : $item['route'];
            }
            $row = [$item['rule'], $item['route'], $item['method'], $item['name']];

            if ($this->input->hasOption('more')) {
                array_push($row, $item['domain'], json_encode($item['option']), json_encode($item['pattern']));
            }
            $rows[] = $row;
        }

        if ($this->input->getOption('sort')) {
            $sort = strtolower($this->input->getOption('sort'));

            if (isset($this->sortBy[$sort])) {
                $sort = $this->sortBy[$sort];
            }

            uasort($rows, function ($a, $b) use ($sort) {
                $itemA = $a[$sort] ?? null;
                $itemB = $b[$sort] ?? null;
                return strcasecmp($itemA, $itemB);
            });
        }

        $table->setRows($rows);

        if ($this->input->getArgument('style')) {
            $style = $this->input->getArgument('style');
            $table->setStyle($style);
        }

        return $this->table($table);
    }

}
