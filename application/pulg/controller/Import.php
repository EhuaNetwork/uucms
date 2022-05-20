<?php


namespace app\pulg\controller;


use app\admin\controller\Common;
use Ehua\Tool\Tool;
use think\Controller;

class Import extends Common
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
        $this->assign('tt', 0);

        return view();
    }

    /**
     * 生成文件夹
     * @param int $i
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * User: Ehua
     * Alter: 2022/2/11 15:16
     */
    public function init($i = 3)
    {
        $id = $i;

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
        $this_type['name'] = "[" . $this_type['id'] . "]" . $this_type['name'];

        foreach ($type2 as $k => $t) {
            $t['name'] = "[" . $t['id'] . "]" . $t['name'];

            if (!empty($this->cache_name[$t['upid']])) {

                $type2[$k]['path'] = $this->cache_name[$t['upid']]['path'] . DS . $t['name'];
            } else {

                $type2[$k]['path'] = $this_type['name'] . DS . $t['name'];
            }
            $this->cache = $t['id'];
            $this->cache_name[$t['id']] = $type2[$k];
        }
        foreach ($type2 as $yy) {
            Tool::create_dir($yy['path']);
        }
        file_put_contents($this_type['name'] . DS . '声明.txt', '每层文件夹对应栏目分类，目录内仅能读取1张jpg或png图片 文件名就是产品名');


        //压缩目录
        $zipnam = TEMP_PATH . 'test.zip';
        $zip = new \ZipArchive();
        $res = $zip->open($zipnam, \ZipArchive::OVERWRITE | \ZipArchive::CREATE);
        if ($res) {
            Tool::zip_ya_dir($this_type['name'], $zip);
            $zip->close();
        }
        Tool::file_deldir(ROOT_PATH . 'public' . DS . $this_type['name']);
        $this->success('已生成');


    }


    /**
     * 导入数据
     * @param string $path
     */
    public $temp = [];

    public function insert()
    {

        try {


            $file = request()->file('file');
            if (empty($file)) {
                return json(['code' => -1, 'msg' => '未获取到文件信息', 'data' => []]);
            }

            $ppath = TEMP_PATH . 'pulg_import' . DS;//解压路径
            Tool::dir_create($ppath);
            Tool::zip_jie_file($file->getInfo('tmp_name'), $ppath);

            $path = $ppath;
            $this->getDir($path);
            $r = $this->temp;
            $newpath = 'uploads' . DS . 'allfile' . DS;
            Tool::create_dir($newpath);
            foreach ($r as $rr) {
                $pathinfo = pathinfo($rr['path']);
                $newfile = $newpath . uniqid() . rand(1000000, 9999999) . '.' . $pathinfo['extension'];
                copy($rr['path'], $newfile);


                $rr['mod'] = explode("/", $rr['mod']);
                $rr['mod'] = array_filter($rr['mod']);

                $rr['mod']= array_reverse($rr['mod']);
                $mod_id=trim( explode("]",$rr['mod'][0])[0],'[');

                $type =$mod_id;

                $data = [
                    'img' => DS . $newfile,
                    'name' => $rr['name'],
                    'type' => $type,
                    'imgs' => [],
                ];

                $data['update_time'] = date('Y-m-d H:i:s', time());
                $data['create_time'] = $data['update_time'];
                if (db('cms_article')->insert($data)) {
//                echo $rr['path'] . ":导入成功";
                } else {
//                echo $rr['path'] . ":导入失败";
                }
            }
            Tool::file_deldir($ppath);
            $this->success('ok');

        } catch (\ErrorException $errorException) {
            $this->error($errorException);
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
                        'mod' => array_reverse(explode(DS, $path))[0],
                    ];
                    $this->temp[] = $arr;

                }
            }
        }

    }


}