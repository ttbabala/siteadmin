<?php
/*
 * 用户管理控制器类（添加用户、编辑用户、用户权限）
 *
 * */
namespace app\admin\controller;
use app\admin\model\Authority;
use app\admin\model\Users;
use think\Model;
use think\Controller;
use think\Request;
use think\Loader;
use app\Common;

class User extends Controller{
    /*----------添加用户--------*/
    public function adduser()
    {
        $this -> assign('title','网站管理-添加用户');
        return $this -> fetch('adduser');
    }

    public function adduserRel()
    {
        $aut = new Authority();
        $autArray = $aut -> select();
        $this -> assign('autArray',$autArray);
        return $this -> fetch('adduserRel');
    }

    public function adduser_cl()                            //添加用户操作
    {
        $request = Request::instance();
        if($request -> has('hidconfirm','post') == 1){
            $username = $request -> post('username');
            $password = $request -> post('password');
            $password_confirm = $request -> post('password_confirm');
            $email = $request -> post('email');
            $status = $request -> post('status');
            $authority = $request -> post('authority');
            $data = [                                       //需要验证的数组字段
                'username' => $username,
                'password' => $password,
                'password_confirm' => $password_confirm,
                'email' => $email,
                'status' => $status,
                'authority' => $authority,
            ];
            $validate = Loader::validate('UserRegister');
            if( !$validate -> check($data) ){
                dump( $validate -> getError() );
            }else{
                $data = [
                    'user_id' => '',
                    'user_aid' => $authority,
                    'user_name' => $username,
                    'user_pass' => sha1(md5($password)+md5($password)),
                    'user_email' => $email,
                    'user_registered' => date('Y-m-d H:i:s',time()),
                    'user_status' => $status,
                    'user_lastlogin' => '',
                ];
                $users = new Users($data);
                $result = $users -> allowField(true) -> save();   //allowField过滤字段
                if( $result == 1 ){
                    $this -> success('用户添加成功！',url('user/adduserRel'));
                }else{
                    $this -> error('用户添加失败！',url('user/adduserRel'));
                }
            }
        }
    }
    /*----------编辑用户--------*/
    public function edituser()
    {
        $this -> assign('title','网站管理-编辑用户');
        return $this -> fetch('edituser');
    }

    public function edituserRel()
    {
        $user = Model('Users');
        $userArray = $user -> select();
        $authority = Model('Authority');
        $autArray = $authority -> select();
        $this -> assign('userArray',$userArray);
        $this -> assign('autArray',$autArray);
        return $this -> fetch('edituserRel');
    }

    public function edituser_cl($type = NULL ,$uid = 0)
    {
        $request = Request::instance();
        if(($request -> has('hidDeleteALL','post')!== 1)
            &&($type !== NULL && $uid !== 0)){
            switch($type){
                case 'edit':                    //传递变量类型为edit编辑
                    echo 'this is edit';
                    break;
                case 'delete':                  //传递变量类型为delete删除
                    $user = Model('Users');
                    $Res = $user -> where('user_id',$uid) -> delete();
                    if( $Res == 1 ){
                        $this -> success('id为'.$uid.'的用户已被删除！',url('user/edituserRel'));
                    }else{
                        $this -> error('用户删除失败！',url('user/edituserRel'));
                    }
                    break;
                case 'preview':                 //传递变量类型为preview预览
                    break;
            }
        }elseif($request -> has('hidDeleteAll','post') == 1){
            $uidArray = $_POST['check'];  //此处用$request -> post('check'),则接收不到checkbox值；
            $user = new Users();
            $Res = $user -> destroy($uidArray);  //destroy(data)函数可批量删除,delete()则需与where等条件语句搭配
            $uidStr = '';
            if(count($Res)!==0){
                foreach($uidArray as $arr){
                    $uidStr .= $arr.',';            //遍历数组并将其转变为字符串
                }
                $useridDel= substr($uidStr, 0, -1); //去掉字符串最后的","
                $this -> success('id为'.$useridDel.'的用户已被批量删除！',url('user/edituserRel'));
            }else{
                $this -> error('用户批量删除失败！',url('user/edituserRel'));
            }
        }
        if($request -> has('editthis','post') == 1){      //处理单个用户的编辑
            $commonExt = new \common();                 //实例化自定义类对象
            $userID = $request -> post('userid');
            $uname = $request -> post('username');
            $upass = $request -> post('password');
            $upass_confirm = $request -> post('password_confirm');
            $uemail = $request -> post('email');
            $ustatus = $request -> post('status');
            $uaid = $request -> post('authority');
            if($upass == '' && $upass_confirm == ''){
                $cdata = [
                    'username' => $uname,
                    'email' => $uemail,
                ];
                $data = [
                    'user_aid' => $uaid,
                    'user_name' => $uname,
                    'user_email' => $uemail,
                    'user_status' => $ustatus,
                ];
            }else{
                $cdata =[
                    'username' => $uname,
                    'password' => $upass,
                    'password_confirm' => $upass_confirm,
                    'email' => $uemail,
                ];
                $data = [
                    'user_aid' => $uaid,
                    'user_name' => $uname,
                    'user_pass' => $commonExt -> sha1md5($upass), //调用自定义类加密函数
                    'user_email' => $uemail,
                    'user_status' => $ustatus,
                ];
            }
            $user = new Users();
            $validate = Loader::validate('UserEdit');
            if( !$validate -> check($cdata) ){
                dump( $validate -> getError() );
            }else{
                $Res = $user -> allowField(true) -> save($data,['user_id' => $userID]);
                if($Res == 1){
                    $this -> success('id为'.$userID.'的用户信息已经更新完成！',url('user/edituserRel'));
                }else{
                    $this -> error('用户信息更新失败！',url('user/edituserRel'));
                }
            }
        }
    }

    /*----------用户权限--------*/
    public function addAuthority(){
        $this -> assign('title','添加用户权限');
        return $this -> fetch();
    }

    public function addAuthorityRel(){
        $this -> assign('modelname','添加角色权限');
        return $this -> fetch();
    }

    public function addAuthority_cl(){
        $request = Request::instance();
        if( $request -> has('hidconfirm','post') &&
            $request -> post('hidconfirm') == 1 ){
            $authname = $request -> post('authname');
            $authtype = $request -> post('authtype');
            $cdata = [
                'authority_name' => $authname,
                'authority_type' => $authtype,
            ];
            $validate = Loader::validate('AddAuthority');
            if( !$validate -> check($cdata) ){
                dump( $validate -> getError() );
            }else{
                $auth = new Authority();
                $data = [
                    'authority_name' => $authname,
                    'authority_type' => $authtype,
                ];
                $Res = $auth -> allowField(true) -> save($data);
                if( $Res == 1){
                    $this -> success('名称为&nbsp;'.$authname.'&nbsp;的权限添加完成！',url('user/addAuthorityRel'));
                }else{
                    $this -> error('权限添加失败！',url('user/addAuthorityRel'));
                }
            }
        }else{
            $this -> error('添加权限的表单未被提交，请返回重新操作！');
        }
    }

    public function editAuthority(){
        $this -> assign('title','网站管理-编辑角色权限');
        return $this -> fetch();
    }

    public function editAuthorityRel(){
        $this -> assign('modelname','编辑角色权限');
        $auth = new Authority();
        $authArray = $auth -> select();
        $this -> assign('authArray',$authArray);
        return $this -> fetch();
    }

    public function editAuthority_cl()
    {
        $request = Request::instance();
        if($request -> has('editAuthority','post') &&
            $request -> post('editAuthority') == 1)
        {
            $authid = $request -> post('authid');
            $authname = $request -> post('authname');
            $authtype = $request -> post('authority_type');
            $validate = Loader::validate('AddAuthority');
            $cdata = [                          //验证数组
                'authority_name' => $authname,
                'authority_type' => $authtype,
            ];
            if( $validate -> check($cdata) )   //返回真值，验证通过
            {
                $auth = new Authority();
                $data = [
                    'authority_name' => $authname,
                ];
                $Res = $auth -> allowField(true) -> save($data,['authority_id' => $authid]);
                if( $Res == 1 ) {
                    $this -> success('id为&nbsp;'.$authid.'&nbsp;的权限更新完成！',url('user/editAuthorityRel'));
                }else{
                    $this -> error('您什么也没有做，权限没有被您修改！',url('user/editAuthorityRel'));
                }
            }
            else
            {
                $this -> error('错误信息：'.$validate -> getError(),url('user/editAuthorityRel'));
            }
        }
        else
        {
            $this -> error('修改权限的表单未被提交，请返回重新操作！');
        }

    }


}
