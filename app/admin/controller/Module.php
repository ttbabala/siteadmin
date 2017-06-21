<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Modules;
use think\Loader;
use think\Request;
use think\Model;

class Module extends Controller{
    public function addModule(){
        $this -> assign('title','网站管理-添加模块');
        return $this -> fetch();
    }

    public function addModuleRel(){
        $this -> assign('modulename','添加模块');
        return $this -> fetch();
    }

    public function addModule_cl()
    {
        $request = Request::instance();
        if($request -> has('hidconfirm','post') &&
            $request -> post('hidconfirm') == 1)
        {
            $moduleName = $request -> post('modulename');
            $moduleSlug = $request -> post('moduleslug');
            $moduleType = $request -> post('moduletype');
            $cdata = [
                'model_name' => $moduleName,
                'model_slug' => $moduleSlug,
            ];
            $validate = Loader::validate('AddModule');
            if( $validate -> check($cdata) )
            {
                $data = [
                    'model_name' => $moduleName,
                    'model_slug' => $moduleSlug,
                    'model_type' => $moduleType,
                ];
                $modules = new Modules($data);
                $Res = $modules -> allowField(true) -> save();
                if( $Res == 1 ){
                    $this -> success($moduleName.'&nbsp;模块添加成功！',url('Module/addModuleRel'));
                }else{
                    $this -> error('模块添加失败！请检查后重新添加！',url('Module/addModuleRel'));
                }
            }
            else
            {
                $this -> error($validate -> getError(),url('Module/addModuleRel'));
            }
        }
        else
        {
            $this -> error('没有获取到提交的表单信息，请检查表单信息后重新提交',url('Module/addModuleRel'));
        }
    }

    public function editModule(){
        $this -> assign('title','网站管理-编辑模块');
        return $this -> fetch();
    }
    public function editModuleRel(){
        $module = Model('Modules');
        $moduleArray = $module -> select();
        $this -> assign('moduleArray',$moduleArray);
        $this -> assign('modulename','编辑模块');
        return $this -> fetch();
    }

    public function editModule_cl(){

    }



}