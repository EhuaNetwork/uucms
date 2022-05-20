<?php


namespace app\admin\controller;
use think\Db;

/**
 * 无状态留言板
 * Class Echos
 * User: Ehua
 * Alter: 2022/2/11 9:54
 * @package app\admin\controller
 */
class Echos extends Baseadmin
{
    /**
     * 列表
     * @return \think\response\View
     * @throws \think\exception\DbException
     * User: Ehua
     * Alter: 2022/2/11 9:54
     */
    public function lists()
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
    /**
     * 修改
     * @param null $i
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\HttpResponseException
     * User: Ehua
     * Alter: 2022/2/11 9:54
     */
    public function edit($i = null)
    {
        if (empty($i)) {
            $this->error('数据不存在');
        }
        $res = db('cms_echo')->where('id', $i)->find();
        if (empty($res)) {
            $this->error('数据不存在');
        }

        $this->assign('data', $res);
        return view();
    }


    public function update()
    {

        $id = request()->param('i');
        if (empty($id)) {
            $this->error('信息不完整');
        }
        $data = [
            'name' => request()->param('name'),
            'phone' => request()->param('phone'),
            'area' => request()->param('area'),
            'budget' => request()->param('budget'),
            'description' => request()->param('description'),
        ];
        $data = array_filter($data);
        if (db('cms_echo')->where('id', $id)->update($data)) {
            $this->success('保存完成', 'lists');
        } else {
            $this->error('保存失败');
        }
    }

    /**
     * 删除
     * @param null $i
     * @throws \think\Exception
     * @throws \think\exception\HttpResponseException
     * @throws \think\exception\PDOException
     * User: Ehua
     * Alter: 2022/2/11 9:54
     */
    public function del($i = null)
    {
        if (empty($i)) {
            $this->error('数据不存在');
        }

        if (db('cms_echo')->where('id', $i)->delete()) {
            $this->success('删除完成', 'lists');
        } else {
            $this->error('删除失败');
        }

    }

}