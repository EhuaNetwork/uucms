<?php


namespace app\admin\controller;

use think\Db;

/**
 * 管理员
 * Class Admin
 * User: Ehua
 * Alter: 2022/2/11 9:48
 * @package app\admin\controller
 */
class Admin extends Baseadmin
{
    //修改密码
    public function repass()
    {
        if (request()->isPost()) {
            $name = request()->param('name');
            $pass = request()->param('pass');
            $repass = request()->param('repass');
            $i = request()->param('i');

            $info = db('cms_admin')->where('id', $i)->find();
            if (empty($info)) {
                $this->error('用户不存在');
            }
            if (!password_verify($repass, $info['pass'])) {
                $this->error('原密码错误');
            }

            $data = ['name' => $name, 'pass' => password_hash($pass, PASSWORD_BCRYPT)];
            if (db('cms_admin')->where('id', $i)->update($data)) {
                session('server', null);
                $this->success('修改成功');
            } else {
                session('server', null);
                $this->error('修改失败');
            }
        }
        return view();
    }

    //退出登录
    public function out()
    {
        session('server', null);
        $this->redirect($this->admPath . '/login/login');
    }
}