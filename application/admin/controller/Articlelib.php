<?php


namespace app\admin\controller;


use Ehua\Tool\Pinyin;
use think\Db;

/**
 * 文章附表
 * Class Articlelib
 * User: Ehua
 * Alter: 2022/2/11 9:51
 * @package app\admin\controller
 */
class Articlelib extends Baseadmin
{
    /**
     * 列表
     * @param $i
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * User: Ehua
     * Alter: 2022/2/11 9:51
     */
    public function lists($i)
    {
        $data = db('cms_article_lib')->where('aid', $i)->select();
        $this->assign('data', $data);
        $this->assign('i', $i);
        return view();
    }


    /**
     * 修改
     * @throws \think\Exception
     * @throws \think\exception\HttpResponseException
     * @throws \think\exception\PDOException
     * User: Ehua
     * Alter: 2022/2/11 9:51
     */
    public function save()
    {

        $data = request()->param();
        $i = $data['i'];
        unset($data['i']);
        foreach ($data as $k => $y) {
            if (!empty($y)) {
                db('cms_article_lib')->where('aid', $i)->where('lib_key', $k)->update(['lib_value' => $y]);
            }
        }
        $this->success('保存成功', 'load');
    }

    /**
     * 同步字段
     * @param $i
     * @throws \think\exception\HttpResponseException
     * User: Ehua
     * Alter: 2022/2/11 9:51
     */
    public function tbparam($i)
    {
        $type = \db('cms_article')->where('id', $i)->value('type');

        $ids = \db('cms_article')->where('type', $type)->column('id');
        $ids = array_flip($ids);
        unset($ids[$i]);
        $ids = array_flip($ids);
        $new_r = \db('cms_article_lib')->whereIn('aid', $ids)->Distinct(true)->field('lib_key')->column('lib_as');

        $ree = \db('cms_article_lib')->whereIn('aid', $i)->Distinct(true)->field('lib_key')->column('lib_as');

        $new_r = array_flip($new_r);
        foreach ($ree as $dat) {
            unset($new_r[$dat]);
        }

        $data = [];
        $new_r = array_flip($new_r);
        foreach ($new_r as $dat) {
            $lib_key = implode('', Pinyin::get_all_py($dat));
            $data[] = [
                'aid' => $i,
                'lib_as' => $dat,
                'lib_key' => $lib_key,
                'lib_value' => '',
            ];
        }
        if (db('cms_article_lib')->insertAll($data)) {
            $this->success('同步完成');
        } else {
            $this->error('同步失败');
        }

    }

    /**
     * 删除
     * @param null $i
     * @throws \think\Exception
     * @throws \think\exception\HttpResponseException
     * @throws \think\exception\PDOException
     * User: Ehua
     * Alter: 2022/2/11 9:51
     */
    public function del($i = null)
    {
        if (empty($i)) {
            $this->error('数据不存在');
        }
        $i = str_replace('.html', '', $i);
        if (db('cms_article_lib')->where('id', $i)->delete()) {
            $this->success('删除完成');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 全部删除
     * @param null $i
     * @throws \think\Exception
     * @throws \think\exception\HttpResponseException
     * @throws \think\exception\PDOException
     * User: Ehua
     * Alter: 2022/2/11 9:52
     */
    public function alldel($i = null)
    {
        if (empty($i)) {
            $this->error('数据不存在');
        }
        $i = str_replace('.html', '', $i);
        $lib_key = \db('cms_article_lib')->where('id', $i)->value('lib_key');

        if (db('cms_article_lib')->where('lib_key', $lib_key)->delete()) {
            $this->success('删除完成');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 新增
     * @param $i
     * @return \think\response\View
     * User: Ehua
     * Alter: 2022/2/11 9:52
     */
    public function addparam($i)
    {
        $this->assign('i', $i);

        if (request()->isPost()) {
            $data = request()->param();
            $data['aid'] = $data['i'];
            unset($data['i']);

            $lib_key = implode('', Pinyin::get_all_py($data['lib_as']));
            $data['lib_key'] = $lib_key;

            if (db('cms_article_lib')->insert($data)) {
                echo "<script> var index = parent.layer.getFrameIndex(window.name);
    parent.layer.close(index);  </script> ";
            } else {
                echo "<script> var index = parent.layer.getFrameIndex(window.name);
    parent.layer.close(index);  </script> ";
            }
        } else {
            return view();
        }
    }
}