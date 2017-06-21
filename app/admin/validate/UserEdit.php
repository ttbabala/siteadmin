<?php
/*
 * UserEdit验证器类 负责验证用户登录信息
 * 控制器类中的方法可通过Loader::validate('UserEdit')实例化UserEdit验证器类对象
 * */
namespace app\admin\validate;

use think\Validate;

class UserEdit extends Validate{
    protected $rule = [                         //验证规则
        'username' => 'min:5|max:25',
        'password' => 'min:6|max:20',
        'password_confirm' => 'min:6|max:20|confirm:password',  //确认与password字段输入值是否一致
        'email' => 'email',  //内置邮箱验证规则 /^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/
    ];

    protected $message = [                      //验证返回信息
        'username.min' => '用户名不能小于6个字符',
        'username.max' => '用户名不能大于20个字符',
        'password.min' => '密码不能小于6个字符',
        'password.max' => '密码不能大于20个字符',
        'password_confirm.min' => '确认密码不能小于6个字符',
        'password_confirm.max' => '确认密码不能大于20个字符',
        'email.email' => '邮箱格式错误',
        'password_confirm.confirm' => '两次输入的密码不相等',
    ];

}