<?php


namespace app\admin\controller;

use app\data\mod\Mod;
use think\Db;
use Tool\Tree;

/**
 * 文章
 * Class Article
 * User: Ehua
 * Alter: 2022/2/11 9:50
 * @package app\admin\controller
 */
class Article extends Baseadmin
{


    /**
     * 列表
     * @param null $t
     * @param null $key
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * User: Ehua
     * Alter: 2022/2/11 9:50
     */
    public function lists($t = null, $key = null)
    {
        $prefix = $this->prefix;


        $data = db('cms_article')->join('cms_mod', "cms_mod.id={$prefix}cms_article.type");
        if (!empty($t)) {
            $ts = Mod::gettypelist($t, true);
            $ts = array_column($ts, 'id');
            array_push($ts, $t);
            $data = $data->whereIn($prefix . 'cms_article.type', $ts);
        }
        if (!empty($key)) {
            $data = $data->where($prefix . 'cms_article.name', 'like', "%{$key}%");
        }
        $data = $data->field("{$prefix}cms_article.name,{$prefix}cms_article.img,{$prefix}cms_article.update_time,{$prefix}cms_article.body,{$prefix}cms_article.view,{$prefix}cms_article.id,{$prefix}cms_article.top,cms_mod.name as type_name")
            ->order($prefix . 'cms_article.top', 'desc')
            ->order($prefix . 'cms_article.create_time', 'desc')
            ->paginate(10, false, ['query' => request()->param()]);
        $render = $data->render();
        $types = db('cms_mod')->order('top', '')->field('id,upid,name')->select();
        function sort($data, $pid = 0, $level = 0)
        {
            static $arr = array();
            foreach ($data as $k => $v) {
                if ($v['upid'] == $pid) {
                    $v['level'] = $level;
                    $arr[] = $v;
                    sort($data, $v['id'], $level + 1);
                }
            }
            return $arr;
        }

        $type2 = sort($types);
        $this->assign('render', $render);
        $this->assign('data', $data);
        $this->assign('tt', $t);
        $this->assign('type2', $type2);
        return view();
    }

    /**
     * 新增
     * @param null $type
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\HttpResponseException
     * User: Ehua
     * Alter: 2022/2/11 9:49
     */
    public function create($type = null)
    {
        if (request()->isPost()) {
            $this->assign('i', request()->param('i'));


            $data = request()->param();
            if(empty($data['imgs'])){
                $data['imgs']=[];
            }
            $data['imgs'] = json_encode($data['imgs'], 256);

            unset($data['file']);
            $data['update_time'] = date('Y-m-d H:i:s', time());
            $data['create_time'] = $data['update_time'];
            if (db('cms_article')->insert($data)) {
                $this->success('发布完成');
            } else {
                $this->error('发布失败');
            }
        } else {
            $types = db('cms_mod')->order('top', '')->field('id,upid,name')->select();
            function sort($data, $pid = 0, $level = 0)
            {
                static $arr = array();
                foreach ($data as $k => $v) {
                    if ($v['upid'] == $pid) {
                        $v['level'] = $level;
                        $arr[] = $v;
                        sort($data, $v['id'], $level + 1);
                    }
                }
                return $arr;
            }

            $type2 = sort($types);
            $this->assign('type2', $type2);
            $this->assign('type', $type);

            return view();
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
     * Alter: 2022/2/11 9:49
     */
    public function edit($i = null)
    {
        if (empty($i)) {
            $this->error('数据不存在');
        }
        $res = db('cms_article')->where('id', $i)->find();
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
        $data = request()->param();
        if(empty($data['imgs'])){
            $data['imgs']=[];
        }
        $data['imgs'] = json_encode($data['imgs'], 256);
        $data = array_filter($data);
        unset($data['i']);

        $data['d'] = request()->param('d');
        $data['k'] = request()->param('k');
        $data['update_time'] = date('Y-m-d H:i:s', time());
        if (db('cms_article')->where('id', $id)->update($data)) {
            echo "<script> var index = parent.layer.getFrameIndex(window.name);
    parent.layer.close(index);  </script> ";
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
     * Alter: 2022/2/11 9:50
     */
    public function del($i = null)
    {
        $arr = explode(',', $i);
        if (db('cms_article')->whereIn('id', $arr)->delete()) {
            $this->success('删除完成');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 移动
     * @param null $i
     * @param null $t
     * @throws \think\Exception
     * @throws \think\exception\HttpResponseException
     * @throws \think\exception\PDOException
     * User: Ehua
     * Alter: 2022/2/11 9:50
     */
    public function settype($i = null, $t = null)
    {
        $arr = explode(',', $i);
        if (db('cms_article')->whereIn('id', $arr)->update(['type' => $t])) {
            $this->success('移动完成');
        } else {
            $this->error('移动失败');
        }
    }

    /**
     * 置顶
     * @param null $i
     * @param null $t
     * @throws \think\Exception
     * @throws \think\exception\HttpResponseException
     * @throws \think\exception\PDOException
     * User: Ehua
     * Alter: 2022/2/11 9:50
     */
    public function settop($i = null, $t = null)
    {
        $arr = explode(',', $i);
        if (db('cms_article')->whereIn('id', $arr)->update(['top' => $t])) {
            $this->success('设置完成');
        } else {
            $this->error('设置失败');
        }
    }
}