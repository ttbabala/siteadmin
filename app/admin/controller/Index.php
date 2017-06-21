<?php
/*
 * 管理首页控制器类（负责载入管理后台首页）
 * */

namespace app\admin\controller;
use think\Controller;
use think\Session;


class Index extends Controller{
    public function index()
    {
        if(Session::has('uid')){
            $this -> assign('title','网站管理后台');
            return $this -> fetch('index');
        }else{
            $this -> error('您必须先登录，方可进入网站后台');
        }
    }
}
