<?php


namespace app\admin\controller;

use think\Db;

/**
 * 友情链接
 * Class Link
 * User: Ehua
 * Alter: 2022/2/11 9:57
 * @package app\admin\controller
 */
class Link extends Baseadmin
{
    /**
     * 列表
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * User: Ehua
     * Alter: 2022/2/11 9:57
     */
    public function lists()
    {
        if (request()->isAjax()) {
            $data = db('cms_link');

            //条件
            if (request()->has('searchParams')) {
                $where = request()->param('searchParams');
                $where = json_decode($where, true);
                $where = array_filter($where);
            } else {
                $where = [];
            }
            //
            $all_page = count($data->where($where)->select()) / request()->param('limit');//总页数

            $data = $data
                ->where($where)
                ->field('id,name,url')
                ->limit((request()->param('page') - 1) * request()->param('limit'), request()->param('limit'))
                ->order('top', 'desc')->order('create_time', 'desc')
                ->select();
            return json([
                'code' => 0,
                'msg' => '',
                'count' => $all_page,
                'data' => $data,
            ]);
        }


        return view();
    }

    /**
     * 新增
     * @return \think\response\View
     * @throws \think\exception\HttpResponseException
     * User: Ehua
     * Alter: 2022/2/11 9:58
     */
    public function create()
    {
        if (request()->isPost()) {
            $data = [
                'name' => request()->param('name'),
                'url' => request()->param('url'),
                'top' => request()->param('top'),
            ];
            if (db('cms_link')->insert($data)) {
                $this->success('添加完成', 'lists');
            } else {
                $this->error('添加失败');
            }
        }
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
     * Alter: 2022/2/11 9:58
     */
    public function edit($i = null)
    {
        if (empty($i)) {
            $this->error('数据不存在');
        }
        $res = db('cms_link')->where('id', $i)->find();
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
            'url' => request()->param('url'),
            'top' => request()->param('top'),
        ];
        $data = array_filter($data);
        if (db('cms_link')->where('id', $id)->update($data)) {
            $this->success('保存完成', 'lists');
        } else {
            $this->error('保存失败');
        }
    }

    public function del($i = null)
    {
        if (empty($i)) {
            $this->error('数据不存在');
        }

        if (db('cms_link')->where('id', $i)->delete()) {
            $this->success('删除完成', 'lists');
        } else {
            $this->error('删除失败');
        }

    }

}