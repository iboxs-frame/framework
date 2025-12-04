<?php
namespace iboxs\convert;

use iboxs\exception\HttpResponseException;
use iboxs\facade\View;
use iboxs\Response;

trait Convert{
    public function endResponse(Response $response){
        throw new HttpResponseException($response);
    }

    public function endJson($jsonArr,$statusCode=200,$header=[]){
        return $this->endResponse(json($jsonArr,$statusCode,$header));
    }

    public function end($content,$statusCode=200,$header=[],$type='html',$trace=true){
        return $this->endResponse(response($content,$statusCode,$header,$type,$trace));
    }

    protected function fetch($template='',$vars=[],$code=200,$trace=true,$filter=null){
        $this->assign('host',env('HOST'));
        return view($template,$vars,$code,$trace,$filter);
    }

    protected function assign($key,$value=null){
        return View::assign($key,$value);
    }

    public function json($code=0,$msg='操作成功',$data=[],$other=[],$state=200){
        $result=[
            'code'=>$code
        ];
        $result['msg']=$msg;
        $result['data']=$data;
        foreach($other as $k=>$v){
            $result[$k]=$v;
        }
        if(request()->param('request_id','')!=''){
            $result['response_id']=request()->param('request_id');
        }
        return json($result,$state);
    }

    public function success($result=[],$msg='操作成功',$other=[],$code=0){
        return $this->json($code,$msg,$result,$other);
    }

    public function error($msg,$code=-403.1,$other=[],$data=[]){
        return $this->json($code,$msg,$data,$other);
    }

    public function listData($data,$map=null){
        $list=$data->select();
        if($map!=null){
            $list=$list->map($map);
        }
        return $list;
    }

    public function listPage($data,$map=null){
        $count=$data->count();
        $limit=request()->post('limit',25);
        $page=request()->post('page',1);
        $page=intval($page);
        $limit=intval($limit);
        $list=$data->page($page,$limit)->select();
        if($map!=null){
            $list=$list->map($map);
        }
        $maxPage=ceil($count/$limit);
        return [
            'data'=>$list,
            'count'=>$count,
            'limit'=>$limit,
            'page'=>$page,
            'maxPage'=>$maxPage
        ];
    }
    
    public function layJson($data,$map=null,$limit=null,$page=null,$other=null){
        $count=$data->count();
        if($limit==null){
            $limit=request()->post('limit',15);
        }
        if($page==null){
            $page=request()->post('page',1);
        }
        $page=intval($page);
        $limit=intval($limit);
        $list=$data->page($page,$limit)->select();
        if($map!=null){
            $list=$list->map($map);
        }
        $maxPage=ceil($count/$limit);
        $other=$other==null?['count'=>$count,'limit'=>$limit,'page'=>$page,'maxPage'=>$maxPage]:array_merge($other,['count'=>$count,'limit'=>$limit,'page'=>$page,'maxPage'=>$maxPage]);
        return $this->success($list,'获取成功',$other);
    }

    protected function jsFetch($vars=[],$code=200,$filter=null){
        $controller=$this->request->controller(true);
        $action=$this->request->action(true);
        $tmp=app_path()."/view/{$controller}/{$action}.js";
        return $this->fetch($tmp,$vars,$code,false,$filter)->header([
            'Content-Type'=>'application/javascript; charset=utf-8'
        ]);
    }
}