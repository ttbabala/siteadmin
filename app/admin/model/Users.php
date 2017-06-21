<?php
/*
 * User表模型
 * */
namespace app\admin\model;
use think\Model;

class Users extends Model{
    protected $pk = 'user_id';

    public function getUserstatusAttr($value){ //自动完成，判断用户状态
        $user_status = [1=>'正常',0=>'挂起'];
        return $user_status[$value];
    }

    public function getUseraidAttr($value){  //自动完成，判断用户权限类型
        $user_aid = [1=>'注册用户',2=>'编辑用户',0=>'管理员'];
        return $user_aid[$value];
    }

    public function authority(){
        $this -> hasOne('authority','authority_type');  //user_aid对应authority_type
    }

}