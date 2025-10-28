<?php
// +----------------------------------------------------------------------
// | iboxsPHP [ WE CAN DO IT JUST iboxs IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2025 http://iboxsphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace iboxs\console\command;

use Composer\InstalledVersions;
use iboxs\console\Command;
use iboxs\console\Input;
use iboxs\console\Output;

class Version extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('version')
            ->setDescription('show iboxsphp framework version');
    }

    protected function execute(Input $input, Output $output)
    {
        $version = InstalledVersions::getPrettyVersion('iboxs/framework');
        $output->writeln($version);
    }

}
