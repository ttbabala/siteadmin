<?php
/*
 * Authority权限表模型
 * */
namespace app\admin\model;
use think\Model;

class Authority extends Model{
    protected $pk = 'authority_id';

    /* public function getauthoritytypeAttr($value){  //自动完成，判断用户权限类型
        $authority_type = [1=>'注册用户 | 不具有后台管理权限',2=>'编辑 | 后台编辑、发布文章',0=>'管理员 | administrator'];
        return $authority_type[$value];
    } */

}