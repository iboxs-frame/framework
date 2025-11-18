<?php
namespace iboxs\convert;

use iboxs\facade\View;

trait Helper{
    public function areaCodeSelect(&$query,$code,$column){
        $center=substr($code,2,2);
        if($center=='90'){
            $query->where($column,$code);
        } else{
            $query->where($column,'like',substr($code,0,4)."%");
        }
    }
}