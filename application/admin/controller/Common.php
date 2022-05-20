<?php


namespace app\admin\controller;


use think\Controller;
use think\Db;

/**
 * 中间件
 * Class Common
 * User: Ehua
 * Alter: 2022/2/11 9:53
 * @package app\admin\controller
 */
class Common extends Controller
{

    public $admPath;

    public function _initialize()
    {
        $system = get_system();
        $this->assign('system', $system);

        $system_admin = Db::name('cms_system_admin')->select();
        $this->assign('system_admin', $system_admin);

        $this->assign('pubPath', config('view_replace_str.__UUCMS__').'/uucms/server/layuimini/');
        $this->assign('pubPath_domain', config('view_replace_str.__UUCMS__'));
        $this->admPath = explode("\\", __CLASS__)[1];
        $this->assign('admPath', $this->admPath);
    }

}