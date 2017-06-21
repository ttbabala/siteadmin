<?php
/*
 * 用户登录控制器类（负责加载用户登录界面，并验证、处理用户登录信息）
 * */
namespace app\admin\controller;
use app\admin\model\Users;
use think\Controller;
use think\Loader;
use think\Request;
use think\Model;
use think\Session;

class Login extends Controller{
    public function index( $session_pull = NULL ){
        if($session_pull == 1){
                Session::pull('uid');                       //如果$session_pull为1，删除session;
        }
        $this -> assign('title','网站后台登录');
        return $this -> fetch('index');
    }

    public function login_cl(){
        $request = Request::instance();
        if($request -> has('username','post') == 1 &&      //判断username,password变量是否存在
            $request -> has('password','post') == 1 ){
            $username = $request -> post('username');
            $password = $request -> post('password');
            $captcha = $request -> post('captcha');
            $data = [                                       //需要验证的数组字段
                'username' => $username,
                'password' => $password,
                'captcha' => $captcha,
            ];
            $validate = Loader::validate('UserLogin');           //实例化验证器类
            if(!$validate -> check($data)){                 //调用check方法验证用户名及密码填写格式是否正确
                dump($validate -> getError());
            }else{
                $uname = $username;
                $pwd_native= $password;
                $pwd= sha1(md5($pwd_native)+md5($pwd_native)); //sha1加密后类型为string（40）
                $user = new Users();
                $userObj= $user -> where('user_name',$uname)
                        -> where('user_pass',$pwd)
                        -> find();
                if($userObj !== NULL){
                    $uid = $userObj['user_id'];
                    Session::set('uid',$uid);                   //session赋值，当前作用域;
                    $this -> success('登录成功',url('index/index')); //将username变量作为参数传递给Index控制器的index方法
                }else{
                    $this -> error('登录失败，请检查您的用户名及密码是否正确！');
                }
            }
        }
    }

}