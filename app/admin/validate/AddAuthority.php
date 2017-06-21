<?php
/*
 * AddAuthority验证器类 负责验证添加的权限信息
 * 控制器类中的方法可通过Loader::validate('AddAuthority')实例化AddAuthorityt验证器类对象
 * */

namespace app\admin\validate;
use think\Validate;

class AddAuthority extends Validate{
    protected $rule = [
        'authority_name' => 'require|min:12|max:18',
        'authority_type' => 'require'
    ];

    protected $message = [
        'authority_name.require' => '权限名称必须填写',
        'authority_name.min' => '权限名称不能小于4个中文字符',
        'authority_name.max' => '权限名称不能大于6个中文字符',
        'authority_type.require' => '必须选择一项权限类型'
    ];
}