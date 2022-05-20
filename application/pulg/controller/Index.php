<?php


namespace app\pulg\controller;


use think\Controller;

class Index
{
    /**
     * 插件加载入口
     * @param null $name 插件名
     * @param null $route 插件方法
     * User: Ehua
     * Date: 2022/2/10 11:02
     */
    public function index($name = null, $route = null)
    {


    }


    public function authority()
    {
        $data = db('cms_echo');

        if (!empty($key)) {
            $data = $data->where('phone', 'like', "%{$key}%");
        }
        if (!empty($name)) {
            $data = $data->where('name', 'link', "%{$name}%");
        }

        $data = $data->field('id,name,phone,create_time,description,ip')->order('create_time', 'desc')->paginate(5, false, ['query' => request()->param()]);

        $render = $data->render();
        $this->assign('render', $render);
        $this->assign('data', $data);

        return view();
    }

    public function mine()
    {

        return view();
    }
}