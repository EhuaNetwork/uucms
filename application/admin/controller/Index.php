<?php


namespace app\admin\controller;

use think\Db;
use think\Exception;
use think\exception\ErrorException;
use think\exception\TemplateNotFoundException;

class Index extends Baseadmin
{
    public function index()
    {
        return view();
    }
    public function system(){
        if(request()->isPost()){
            $data=request()->param();
            foreach($data as  $k=>$dat){
                db('cms_system_admin')->where('key',$k)->update(['value'=>$dat]);
            }
            $this->success('保存成功', 'system');

        }else{
            $sys = db('cms_system_admin')->select();
            $this->assign('sys', $sys);
            return view();
        }

    }

    /**
     * 添加自变量
     * @return \think\response\View
     * User: Ehua
     * Alter: 2022/2/11 9:54
     */
    public function addparam()
    {
        if (request()->isPost()) {

            $key = request()->param('key');
            $as = request()->param('as');
            $value = request()->param('value');

            if (db('cms_system')->insert(['key' => $key, 'as' => $as, 'value' => $value])) {
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

    /**
     * seo设置
     * @return \think\response\View
     * User: Ehua
     * Alter: 2022/2/11 9:55
     */
    public function seo()
    {
        $path1 = ROOT_PATH . 'application/data/tpl/index';
        if (!file_exists($path1)) {
            file_put_contents($path1, 'false');
            $Pststic = 'false';
        } else {
            $Pststic = file_get_contents($path1);
        }


        $this->assign('Pststic', $Pststic);
        return view();
    }

    /**
     * seo保存
     * @param null $status
     * @throws \think\exception\HttpResponseException
     * User: Ehua
     * Alter: 2022/2/11 9:55
     */
    public function Pseosave($status = null)
    {
        if (!in_array($status, ['false', 'true'])) {
            $this->error('信息不符 true false');
        }
        $path = ROOT_PATH . 'application/data/tpl/index';
        file_put_contents($path, $status);
        if ($status == 'false') {
            $this->deldir(ROOT_PATH . 'public/index/');
        }
        $this->success('修改成功');
    }



    //删除文件夹
    private function deldir($path)
    {
        try {
            if (is_dir($path)) {
                //扫描一个文件夹内的所有文件夹和文件并返回数组
                $p = scandir($path);
                foreach ($p as $val) {
                    //排除目录中的.和..
                    if ($val != "." && $val != "..") {
                        //如果是目录则递归子目录，继续操作
                        if (is_dir($path . $val)) {
                            //子目录中操作删除文件夹和文件
                            $this->deldir($path . $val . '/');
                            //目录清空后删除空文件夹
                            @rmdir($path . $val . '/');
                        } else {
                            //如果是文件直接删除
                            unlink($path . $val);
                        }
                    }
                }
            } else {
                unlink($path);
            }
            @rmdir($path);
        } catch (ErrorException $errorException) {
//            var_dump($errorException->getMessage());
            return false;
        }
    }

    /**
     * 控制台入口
     * @return \think\response\View
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * User: Ehua
     * Alter: 2022/2/11 9:56
     */
    public function controll()
    {
//        @file_get_contents("http://uucms.ehuanetwork.com/api/Verify/init/i/" . @gethostbyname(@$_SERVER['SERVER_NAME']) . "/d/" . @$_SERVER['SERVER_NAME']);

        $echos = \db('cms_echo')->order('id', 'desc')->limit(0, 5)->select();
        $this->assign('echos', $echos);


        $count = [];
        $count['article'] = \db('cms_article')->count();
        $count['user'] = \db('cms_user')->count();
        $count['view'] = \db('cms_view')->count();
        $count['echo'] = \db('cms_echo')->count();
        $this->assign('count', $count);

        return view();
    }

    /**
     * seo清理缓存
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\HttpResponseException
     * User: Ehua
     * Alter: 2022/2/11 9:56
     */
    public function Pdel()
    {

        $mod = db('cms_mod')->where('upid', 0)->order('upid_all', 'desc')->select();
        $this->deldir(ROOT_PATH . 'public' . DS . 'index.html');
        $this->deldir(ROOT_PATH . 'public/index/');

        foreach ($mod as $no) {
            if ($no['nourl'] != '/' && $no['nourl'] != '\\') {
                if ($no['nourl'] != '' && $no['nourl'] != null) {
                    $this->deldir(ROOT_PATH . 'public' . DS . $no['nourl']);
                } else {
                    $this->deldir(ROOT_PATH . 'public' . DS . $no['c'] . DS);
                }
            }
        }
        $this->success('更新成功', $this->admPath . '/index/seo');

    }


    function file_put_contents_ehua($path, $body)
    {
        $path = explode(DS, $path);
        $temp = array_pop($path);
        $path = implode('/', $path);
        $this->dir_create($path);
        file_put_contents($path . DS . $temp . '.html', $body);
    }

    /**
     * 递归创建文件目录
     * @param $dir
     */
    function dir_create($dir)
    {
        if (is_dir($dir) || @mkdir($dir, 0777)) {
        } else {
            $this->dir_create(dirname($dir));
            if (@mkdir($dir, 0777)) {
            }
        }
    }


    /**
     * 生成缓存
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\HttpResponseException
     * User: Ehua
     * Alter: 2022/2/11 9:57
     */
    public function Pcheck()
    {

        //更新首页
        try {
            $data = new \app\index\controller\Index();
            $data->index();
        } catch (TemplateNotFoundException $exception) {
            $HTML = $this->fetch('index@index/index');//获得页面HTML代码
            $HTML = str_replace('="/index/', '="/', $HTML);
            $HTML = str_replace('="/index/index.html', '="/', $HTML);

            file_put_contents('index.html', $HTML);
        }

        //更新栏目
        $mod = db('cms_mod')->order('upid', 'desc')->order('nourl', 'desc')->select();
        foreach ($mod as $m) {
            try {
                eval("\$data = new \\app\\" . $m['m'] . "\\controller\\" . ucfirst($m['c']) . "();");
                eval("\$data->" . $m['a'] . "();");
            } catch (TemplateNotFoundException $exception) {
                $HTML = $this->fetch($m['m'] . '@' . $m['c'] . DS . $m['a']);//获得页面HTML代码
                $HTML = str_replace('="/index/', '="/', $HTML);
                $HTML = str_replace('="/index/index.html', '="/', $HTML);
                if ($m['upid'] != 0) {
                    $this->file_put_contents_ehua($m['c'] . DS . $m['a'] . DS . 't' . DS . $m['id'], $HTML);
                } else {
                    $this->file_put_contents_ehua($m['c'] . DS . $m['a'], $HTML);
                }
            }
        }


        //更新文章


//        file_put_contents()
        $article = \db('cms_article')->join('cms_mod', 'cms_mod.id=' . config('database.prefix') . 'cms_article.type')->field(config('database.prefix') . 'cms_article.*,cms_mod.m,cms_mod.c,cms_mod.a')->select();
        foreach ($article as $a) {
            try {
                eval("\$data = new \\app\\" . $a['m'] . "\\controller\\" . ucfirst($a['c']) . "();");
                eval("\$data->" . 'content' . "(" . $a['id'] . ");");
            } catch (TemplateNotFoundException $exception) {
                $HTML = $this->fetch($a['m'] . '@' . $a['c'] . DS . 'content');//获得页面HTML代码
                $HTML = str_replace('="/index/', '="/', $HTML);
                $HTML = str_replace('="/index/index.html', '="/', $HTML);
                $this->file_put_contents_ehua($a['c'] . DS . $a['a'] . DS . $a['id'], $HTML);
            }
        }

        $this->success('更新成功', $this->admPath . '/index/seo');
    }

    public function del($i = null)
    {
        if (empty($i)) {
            $this->error('数据不存在');
        }
        $i = str_replace('.html', '', $i);
        if (db('cms_system')->where('key', $i)->delete()) {
            $this->success('删除完成');
        } else {
            $this->error('删除失败');
        }

    }
    public function load()
    {
        $sys = db('cms_system')->select();
        $this->assign('sys', $sys);
        return view();
    }
    public function save()
    {
        foreach (request()->param() as $k => $y) {
            if (!empty($y)) {
                db('cms_system')->where('key', $k)->update(['value' => $y]);
            }
        }
        $this->success('保存成功', 'load');
    }
}