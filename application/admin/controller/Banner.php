<?php


namespace app\admin\controller;


/**
 * 首页图片管理
 * Class Banner
 * User: Ehua
 * Alter: 2022/2/11 9:52
 * @package app\admin\controller
 */
class Banner extends Baseadmin
{
    /**
     * 列表
     * @param null $t
     * @param null $key
     * @return \think\response\View
     * @throws \think\exception\DbException
     * User: Ehua
     * Alter: 2022/2/11 9:52
     */
    public function lists($t = null, $key = null)
    {
        $data = db('cms_banner');
        if (!empty($t)) {
            $data = $data->where('style', $t);
        }
        if (!empty($key)) {
            $data = $data->where('name', 'like', "%{$key}%");
        }

        $data = $data->paginate(5, false, ['query' => request()->param()]);

        $render = $data->render();
        $this->assign('render', $render);
        $this->assign('data', $data);


        return view();
    }

    /**
     * 新增
     * @return \think\response\View
     * User: Ehua
     * Alter: 2022/2/11 9:52
     */
    public function create()
    {
        return view();
    }

    public function save()
    {
        $data = [
            'img' => request()->param('img'),
            'img2' => request()->param('img2'),
            'name' => request()->param('name'),
        ];
        if (db('cms_banner')->insert($data)) {
            $this->success('发布完成', 'lists');
        } else {
            $this->error('发布失败');
        }

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
     * Alter: 2022/2/11 9:53
     */
    public function edit($i = null)
    {
        if (empty($i)) {
            $this->error('数据不存在');
        }
        $res = db('cms_banner')->where('id', $i)->find();
        if (empty($res)) {
            $this->error('数据不存在');
        }


        $type = get_menu_type(1);
        $this->assign('type', $type);
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
            'img' => request()->param('img'),
            'img2' => request()->param('img2'),
            'name' => request()->param('name'),
        ];
        $data = array_filter($data);
        $data['d'] = request()->param('d');
        $data['k'] = request()->param('k');
        if (db('cms_banner')->where('id', $id)->update($data)) {
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
     * Alter: 2022/2/11 9:53
     */
    public function del($i = null)
    {
        if (empty($i)) {
            $this->error('数据不存在');
        }

        if (db('cms_banner')->where('id', $i)->delete()) {
            $this->success('删除完成');
        } else {
            $this->error('删除失败');
        }

    }

}