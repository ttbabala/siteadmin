<?php
/*
 * AddModule验证器类 负责验证添加的模块信息
 * 控制器类中的方法可通过Loader::validate('AddModule')实例化AddModule验证器类对象
 * */

namespace app\admin\validate;
use think\Validate;

class AddModule extends Validate{
    protected $rule = [
        'model_name' => 'require|min:6|max:24',
        'model_slug' => 'require|max:300',
    ];

    protected $message = [
        'model_name.require' => '模块名称必须填写',
        'model_name.min' => '模块名称不能小于2个中文字符或6个英文字符',
        'model_name.max' => '模块名称不能大于8个中文字符或24个英文字符',
        'model_slug.require' => '模块描述必须填写',
        'model_slug.max' => '模块描述不能大于100个中文字符或300个英文字符'
    ];
}