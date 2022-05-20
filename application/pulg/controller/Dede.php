<?php


namespace app\pulg\controller;


use app\admin\controller\Common;
use Ehua\Tool\Tool;
use think\Controller;
use think\Db;

/**
 * DeDe数据导入
 */
class Dede extends Common
{
    public $cache = 0;
    public $cache_name = [];

    /**
     * 入口方法
     * @return \think\response\View
     * User: Ehua
     * Alter: 2022/2/12 17:18
     */
    public function into()
    {
        echo <<<eol
    <a href="insmod">导入栏目</a><br>
    <a href="init">导入文章</a><br>
eol;
    }


    /**
     * 插入mod
     * @return void
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function insmod()
    {
        Db::name('cms_mod')->whereNotIn('id',[0])->delete();
        $res = Db::connect([
                'type' => 'mysql',
                'hostname' => config('database.hostname'),
                'database' =>  config('database.database'),
                'username' =>  config('database.username'),
                'password' => config('database.password'),
                'hostport' =>  config('database.hostport'),
                'prefix' => 'dede_'
            ]
        )->table('dede_arctype')
            ->field('topid,id,typename,typedir,content')
            ->select();

        foreach ($res as $r) {

            $data = [
                'id' => $r['id'],
                'name' => $r['typename'],
                'm' => 'index',
                'c' => null,
                'top' => 0,
                'a' => 'index',
                'upid' => $r['topid'],
                'nourl' => str_replace("{cmspath}", '', $r['typedir']),
                'body' => $r['content'],
            ];
            \db('cms_mod')->insert($data);
        }

        echo 'ok!';
    }

    /**
     * @param int $i
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * User: Ehua
     * Alter: 2022/2/11 15:16
     */
    public function init($i = 3)
    {


        $ids = [
            152 => 2,
            153 => 3,
            154 => 4,
            166 => 36,
            167 => 37,
            168 => 39,
            169 => 40,
            170 => 41,
            171 => 38,
            93 => 27,
            94 => 28,
        ];
        $ids = array_flip($ids);

        $res = Db::connect([
                'type' => 'mysql',
                'hostname' => config('database.hostname'),
                'database' =>  config('database.database'),
                'username' =>  config('database.username'),
                'password' => config('database.password'),
                'hostport' =>  config('database.hostport'),
                'prefix' => 'dede_'
            ]
        )->table('dede_archives')
            ->join('addonarticle', 'addonarticle.aid=dede_archives.id', 'left')
            ->join('dede_addonimages', 'dede_addonimages.aid=dede_archives.id', 'left')
            ->field('dede_archives.id,dede_archives.pubdate,dede_archives.typeid,dede_archives.title,dede_archives.litpic,addonarticle.body,dede_addonimages.body as body2')
            ->select();
        function get_tp($ids, $id)
        {
            return $id;
            if (empty($ids[$id])) {
                return 0;
            } else {
                return $ids[$id];
            }
        }

        foreach ($res as $r) {
            $data = [
                'body' => empty($r['body']) ? $r['body2'] : $r['body'],
                'img' => $r['litpic'],
                'name' => $r['title'],
                'id' => $r['id'],
                'update_time' => date('Y-m-d', $r['pubdate']),
            ];
            $data['type'] = $r['typeid'];

            \db('cms_article')->insert($data);
        }
        echo 'ok!';
    }





}