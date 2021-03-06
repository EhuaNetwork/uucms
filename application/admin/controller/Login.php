<?php


namespace app\admin\controller;

use think\Db;

use think\Controller;

/**
 * 登录
 * Class Login
 * User: Ehua
 * Alter: 2022/2/11 9:58
 * @package app\admin\controller
 */
class Login extends Common
{
    public $admPath;

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    public function login()
    {
        return view();
    }

    /**
     * 登录接口
     * @param null $username
     * @param null $password
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * User: Ehua
     * Alter: 2022/2/11 9:59
     */
    public function loginauth($username = null, $password = null)
    {
        if (empty($username) || empty($password)) {
            return json(['code' => -1, 'msg' => '信息不完整', 'data' => []]);
        }

        $info = db('cms_admin')->where('name', $username)->find();
        if (empty($info)) {
            return json(['code' => -1, 'msg' => '账号或密码错误', 'data' => []]);
        }


        if (!password_verify($password, $info['pass'])) {
            return json(['code' => -1, 'msg' => '账号或密码错误', 'data' => []]);
        }

        unset($info['pass']);
        db('cms_admin')->where('id', $info['id'])->update(['ip' => request()->ip()]);
        session('server', $info);
        return json(['code' => 1, 'msg' => '登录成功', 'data' => []]);

    }

}