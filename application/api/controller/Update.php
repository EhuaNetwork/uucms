<?php


namespace app\api\controller;


use Ehua\Tool\Tool;
use think\Controller;

class Update extends Controller
{
    public $cache = 0;
    public $cache_name = [];

    /**
     * 生成文件夹
     * @param int $id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * User: Ehua
     * Alter: 2022/2/11 15:16
     */
    public function init($id = 3)
    {
        $types = db('cms_mod')->order('top', '')->field('id,upid,name,top')->select();
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

        $type2 = sort($types, $id);
        $this_type = db('cms_mod')->where('id', $id)->order('top', '')->field('id,upid,name,top')->find();
        foreach ($type2 as $k => $t) {
            if (!empty($this->cache_name[$t['upid']])) {
                $type2[$k]['path'] = $this->cache_name[$t['upid']]['path'] . DS . $t['name'];
            } else {
                $type2[$k]['path'] = $this_type['name'] . DS . $t['name'];
            }
            $this->cache = $t['id'];
            $this->cache_name[$t['id']] = $type2[$k];
        }
        foreach ($type2 as $yy) {
            $this->create_dir($yy['path']);
        }

        file_put_contents($this_type['name'] . DS . '声明.txt', '每层文件夹对应栏目分类，目录内仅能读取1张jpg或png图片和1个txt格式的文档，txt文件的文件名就是产品名');


    }


    /**
     * 获取文件列表
     * @param string $path
     */
    public $temp = [];

    public function insert($path = '产品展示')
    {

        $this->getDir($path);
        $r = $this->temp;


        $newpath = 'uploads' . DS . 'allfile' . DS;
        $this->create_dir($newpath);
        foreach ($r as $rr) {
            $pathinfo = pathinfo($rr['path']);
            $newfile = $newpath . uniqid() . rand(1000000, 9999999) . '.' . $pathinfo['extension'];
            copy($rr['path'], $newfile);
            $data = [
                'img' => $newfile,
                'name' => $rr['name'],
                'type' => db('cms_mod')->where('name', $rr['mod'])->value('id'),
            ];

            $data['update_time'] = date('Y-m-d H:i:s', time());
            $data['create_time'] = $data['update_time'];
            if (db('cms_article')->insert($data)) {
                echo $rr['path'] . ":导入成功";
            } else {
                echo $rr['path'] . ":导入失败";
            }
            echo "<br>";
        }


    }

    function getDir($path)
    {
        if (is_dir($path)) {
            $dir = scandir($path);
            foreach ($dir as $value) {
                $sub_path = $path . '/' . $value;
                if ($value == '.' || $value == '..') {
                    continue;
                } else if (is_dir($sub_path)) {
//                        echo '目录名:'.$value .'<br/>';
                    $this->getDir($sub_path);
                } else {
                    //.$path 可以省略，直接输出文件名
//                        echo ' 最底层文件: '.$path. ':'.$value.' <hr/>';
//                    $this->temp[]=$path.':'.$value;

                    $arr = [
                        'name' => (explode('.', $value))[0],
                        'path' => $path . DS . $value,
                        'mod' => array_reverse(explode('/', $path))[0],
                    ];
                    $this->temp[] = $arr;

                }
            }
        }
    }


    /**
     * 多级目录创建
     */
    private function create_dir($path)
    {
        //判断目录存在否，存在给出提示，不存在则创建目录
        if (is_dir($path)) {
            echo "对不起！目录 " . $path . " 已经存在！<br>";
        } else {
            //第三个参数是“true”表示能创建多级目录，iconv防止中文目录乱码
            $res = mkdir($path, 0777, true);
            if ($res) {
                echo "目录 $path 创建成功<br>";
            } else {
                echo "目录 $path 创建失败<br>";
            }
        }
    }
}