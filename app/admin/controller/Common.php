<?php
/*
 * 公共控制器类（负责载入left,top,right框架）
 * */
namespace app\admin\controller;
use think\Controller;
use think\Session;
use app\admin\model\Users;

class Common extends Controller{
    public function top(){
        if(Session::has('uid')){
            $uid = Session::get('uid');
            $user = new Users();
            $Res = $user -> where('user_id',$uid) -> find();
            $uname = $Res['user_name'];
            $this -> assign('uname',$uname);
            return $this -> fetch('');
        }else{
            $this -> error('您必须先登录，方可能进入网站后台');
        }
    }

    public function left(){
        return $this -> fetch('');
    }

    public function right(){
        return $this -> fetch('');
    }
}