<?php
/*
 * UserLogin验证器类 负责验证用户登录信息
 * 控制器类中的方法可通过Loader::validate('UserLogin')实例化UserLogin验证器类对象
 * */
namespace app\admin\validate;

use think\Validate;

class UserLogin extends Validate{
    protected $rule = [                         //验证规则
        'username' => 'require|min:5|max:25',
        'password' => 'require|min:6|max:20',
        'captcha' => 'require|captcha',
    ];

    protected $message = [                      //验证返回信息
        'username.require' => '用户名必填',
        'username.min' => '用户名不能小于6个字符',
        'username.max' => '用户名不能大于20个字符',
        'password.require' => '密码必填',
        'password.min' => '密码不能小于6个字符',
        'password.max' => '密码不能大于20个字符',
        'captcha.require' => '验证码不能为空',

    ];
}