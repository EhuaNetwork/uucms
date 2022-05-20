<?php


namespace app\admin\controller;


use Ehua\Tool\Tool;
use think\Controller;
use think\Db;
use function GuzzleHttp\json_decode;

/**
 * 本地插件
 * Class Mine
 * User: Ehua
 * Alter: 2022/2/12 13:42
 * @package app\pulg\controller
 */
class Mine extends Common
{
    public function index()
    {
        $data = db('cms_pulg');
        if (!empty($name)) {
            $data = $data->where('name', 'like', "%{$name}%");
        }
        $data = $data->order('create_time', 'desc')->paginate(5, false, ['query' => request()->param()]);
        $render = $data->render();

        $this->assign('render', $render);
        $this->assign('data', $data);
        if (request()->isPost()) {
            $data = [
                'data' => $data->items(),
                'render' => $render,
            ];
            return json($data);
        } else {
            return view();
        }
    }

    public function create()
    {
        return view();
    }

    public function install($i = null)
    {

        $pulg_info = db('cms_pulg')->where('id', $i)->find();
        $path = ROOT_PATH . DS . $pulg_info['path'];


        /*
             php 从zip压缩文件中提取文件
        */
        $zip = new \ZipArchive;


        if ($zip->open($path) === TRUE) {//中文文件名要使用ANSI编码的文件格式

            $static = [];
            $controller = [];
            $view = [];
            for ($iii = 0; $iii < $zip->count(); $iii++) {
                if (preg_match("/controller/", $zip->getNameIndex($iii))) {
                    array_push($controller, $zip->getNameIndex($iii));
                }
                if (preg_match("/static/", $zip->getNameIndex($iii))) {
                    array_push($static, $zip->getNameIndex($iii));
                }
                if (preg_match("/view/", $zip->getNameIndex($iii))) {
                    array_push($view, strtolower($zip->getNameIndex($iii)));
                }
            }
            $zip->extractTo(APP_PATH . 'pulg', $controller);//提取部分文件
            $zip->extractTo(ROOT_PATH . 'public' . DS . 'plug' . DS . $pulg_info['pulg'], $static);//提取部分文件
            $zip->extractTo(APP_PATH . 'pulg', $view);//提取部分文件

            $zip->close();

            db('cms_pulg')->where('id', $i)->update(['install' => 1]);
            $this->success('安装成功');
        } else {
            $this->error('安装失败');

        }
    }

    /**
     * 插件打包导出
     */
    public function package($i = 12)
    {
        $this->assign('i', $i);

        if (request()->isPost()) {
            function copyDir($dir, $toDir)
            {
                if (!is_dir($toDir)) {
                    mkdir($toDir); //如果$toDir没有创建则创建目录
                }
                if ($handle = @opendir($dir)) {
                    while (($file = readdir($handle)) !== false) {
                        if ($file == '.' || $file == '..') {
                            continue; //避免删除当前目录和上级目录
                        }
                        if (is_dir($dir . '/' . $file)) {
                            //如果是目录就递归
                            copyDir($dir . '/' . $file, $toDir . '/' . $file);
                        } else {
                            //如果是文件直接复制过去
                            copy($dir . '/' . $file, $toDir . '/' . $file);
                        }
                    }
                    closedir($handle);
                }
            }

            $info = Db::name('cms_pulg')->where('id', $i)->find();

            $controller = APP_PATH . 'pulg' . DS . 'controller' . DS . ucfirst($info['pulg']) . '.php';
            $view = APP_PATH . 'pulg' . DS . 'view' . DS . strtolower($info['pulg']) . DS;

            $rand = uniqid();

            Tool::dir_create(TEMP_PATH . 'pulg_dir' . DS . $rand . DS . 'controller');
            Tool::dir_create(TEMP_PATH . 'pulg_dir' . DS . $rand . DS . 'view' . DS . strtolower($info['pulg']));
            copy($controller, TEMP_PATH . 'pulg_dir' . DS . $rand . DS . 'controller' . DS . ucfirst($info['pulg']) . '.php');
            copyDir($view, TEMP_PATH . 'pulg_dir' . DS . $rand . DS . 'view' . DS . strtolower($info['pulg']));

            $data = [
                'pulg_name' => input('pulg_name'),
                'author' => input('author'),
                'describe' => input('describe'),
                'lavel' => input('lavel'),
                'route' => input('route'),
            ];
            file_put_contents(TEMP_PATH . 'pulg_dir' . DS . $rand . DS . 'config.json', json_encode($data, 256));


            $zipPath = TEMP_PATH . 'pulg_dir' . DS . $rand . '.zip';
            $dirPath = TEMP_PATH . 'pulg_dir' . DS . $rand ;

            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
                //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法

                Tool::zip_ya_dir($dirPath, $zip);
                //关闭处理的zip文件
                $zip->close();
            }
            Tool::file_deldir($dirPath);

            Tool::zip_download($zipPath);


//            $this->downloads($zipPath);

        } else {
            return view();
        }


    }


    /**
     * 文件下载
     * @param string $file 文件绝对路径
     */
    function downloads($file)
    {
        str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $file);
        //检查文件是否存在
        if (empty($file) or !is_file($file)) {
            die('文件不存在');
        }
        $fileName = basename($file);
        //以只读和二进制模式打开文件
        $fp = @fopen($file, 'rb');
        if ($fp) {
            // 获取文件大小
            $file_size = filesize($file);
            //告诉浏览器这是一个文件流格式的文件
            header('content-type:application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $fileName);
            // 断点续传
            $range = null;
            if (!empty($_SERVER['HTTP_RANGE'])) {
                $range = $_SERVER['HTTP_RANGE'];
                $range = preg_replace('/[\s|,].*/', '', $range);
                $range = explode('-', substr($range, 6));
                if (count($range) < 2) {
                    $range[1] = $file_size;
                }
                $range = array_combine(array('start', 'end'), $range);
                if (empty($range['start'])) {
                    $range['start'] = 0;
                }
                if (empty($range['end'])) {
                    $range['end'] = $file_size;
                }
            }
            // 使用续传
            if ($range != null) {
                header('HTTP/1.1 206 Partial Content');
                header('Accept-Ranges:bytes');
                // 计算剩余长度
                header(sprintf('content-length:%u', $range['end'] - $range['start']));
                header(sprintf('content-range:bytes %s-%s/%s', $range['start'], $range['end'], $file_size));
                // fp指针跳到断点位置
                fseek($fp, sprintf('%u', $range['start']));
            } else {
                header('HTTP/1.1 200 OK');
                header('Accept-Ranges:bytes');
                header('content-length:' . $file_size);
            }
            while (!feof($fp)) {
                echo fread($fp, 4096);
                ob_flush();
            }
            fclose($fp);
        } else {
            die('File loading failed');
        }
    }

    /**
     * 插件卸载
     * @param null $i
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function del($i = null)
    {
        if (empty($i)) {
            $this->error('数据不存在');
        }

        if (db('cms_pulg')->where('id', $i)->delete()) {
            $this->success('删除完成');
        } else {
            $this->error('删除失败');
        }

    }
}