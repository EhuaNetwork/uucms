<?php

namespace app\admin\controller;

use Ehua\Bt\Curl\Post;
use Ehua\Tool\Tool;
use think\Controller;
use think\Db;

/**
 * 版本更新
 * Class Update
 * @package app\admin\controller
 */
class Update extends Common
{

    //前端更新入口
    public function init()
    {
        $temp = file_get_contents('version');
        $kj_name = explode(' for ', $temp)[0];
        $kj_lv = explode(' for ', $temp)[1];
        $kj_lv = ltrim($kj_lv, 'v');

        $post = new Post();
        $url = UUCMS_SERVER . '/admin/update/getlogs/lv/' . $kj_lv;
        $r = $post->http($url);

        $r = json_decode($r, true);
        if (!$r) {
            $r['render'] = '';
            $r['data'] = [];
        }
        $this->assign('render', $r['render']);
        $this->assign('data', $r['data']);
        return view();
    }


    //外部获取更新接口
    public function getlogs($lv)
    {

        $data = \db('cms_log_update')->where('this_lv', 'like', "%[$lv]%")->where('status', 1)
            ->order('create_time', 'desc')
            ->paginate(5, false, ['query' => request()->param()]);
        $render = $data->render();;

        $data = $data->items();;
        return json(['code' => 200, 'data' => $data, 'render' => $render, 'msg' => 'OK']);

    }


    //外部验证更新资源
    public function ret_paths($i)
    {
        $r = db('cms_log_update')->where('id', $i)->where('share', 1)->find();
        if (!$r) {
            return json(['code' => 500, 'data' => [], 'msg' => '资源失效']);
        } else {
            return json(['code' => 200, 'data' => $r, 'msg' => 'OK']);
        }
    }

    /**
     * 外部获取本地分享的插件接口
     * @param $i
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function ret_path($i)
    {
        $r = db('cms_log_update')->where('id', $i)->where('share', 1)->find();
        if (!$r) {
            $this->error('资源失效');
        } else {
            $this->downloads(ROOT_PATH . $r['path']);
        }
    }

    /**
     * 外部下载更新包
     * @param $i
     * @return void
     * @throws \Ehua\Bt\Curl\CurlException
     */
    public function download($i)
    {

        $urlS = UUCMS_SERVER . 'admin/update/ret_paths/i/' . $i;//资源验证地址

        $post = new Post();
        $r = $post->http($urlS);


        $r = json_decode($r, true);
        if ($r['code'] == 200) {
            $zipPath = ROOT_PATH . 'package' . DS . 'update' . DS . 'uucms_' . $r['data']['to_lv'] . '.zip';
            $url = UUCMS_SERVER . 'admin/update/ret_path/i/' . $i;//直连下载地址

            if (file_put_contents($zipPath, file_get_contents($url))) {
                $path = $zipPath;
                $this->upload($path, $r['data']);
            } else {
                echo 'error-2';
            }
        } else {
            echo 'error-1';
        }
    }


    public $update_log = TEMP_PATH . "/update_log.log"; //系统升级日志
    public $return_log = TEMP_PATH . "/return_log.log"; //系统回滚日志
    public $progress_log = TEMP_PATH . "/progress_log.log"; //记录进度
    public $root_dir = ROOT_PATH; //站点代码的根目录
    public $aFile = ["log", "runtime"];//忽略文件夹相对路径
    public $backup_dir = TEMP_PATH . "/backup_dir";//备份目录
    public $upload_dir = TEMP_PATH . "/upload_dir";//升级包目录
    public $sys_version_num = '1.0.0';//当前系统的版本 这个在实际应用中应该是虫数据库获取得到的，这里只是举个例子

    public function _initialize()
    {
        Tool::dir_create($this->backup_dir);
        Tool::dir_create($this->upload_dir);


        $temp = file_get_contents('version');
        $kj_name = explode(' for ', $temp)[0];
        $kj_lv = explode(' for ', $temp)[1];

        $this->sys_version_num = ltrim($kj_lv, 'v');
    }

    /**
     * 处理升级包上传
     */
    public function upload($path,$http)
    {

        $pathinfo = pathinfo($path);
        $name = $pathinfo['basename'];
        //校验后缀
        $astr = explode('.', $name);
        $ext = array_pop($astr);
        if ($ext != 'zip') {
            echo json_encode(["status" => 0, "msg" => "请上传文件格式不对"]);
            die;
        }


        //对比版本号
        $astr = explode('_', $name);
        $version_num = str_replace(".zip", '', array_pop($astr));
        if (!$version_num) {
            echo json_encode(["status" => 0, "msg" => "获取版本号失败"]);
            die;
        }
        //对比
        $this->to_lv = $version_num;
        if (!$this->compare_version($version_num)) {
            echo json_encode(["status" => 0, "msg" => "不能升级低版本的"]);
            die;
        }
        $package_name = $this->upload_dir . '/' . $version_num . '.zip';

        if (!copy($path, $package_name)) {
            echo json_encode(["status" => 0, "msg" => "上传文件失败"]);
            die;
        }
        unlink($path);

        //记录下日志
        $this->save_log("上传升级包成功！");
        $this->update_progress("20%");
        //备份code
        $result = $this->backup_code();
        if (!$result) {
            $this->save_log("备份失败！");
            echo json_encode(["status" => 0, "msg" => "备份失败"]);
            die;
        }
        $this->update_progress("30%");
        //执行升级
        $this->execute_update($package_name,$http);

        $this->success('更新完成');
    }

    /**
     * 升级操作
     * @return [type] [description]
     */
    private function execute_update($package_name,$http)
    {

        Tool::zip_jie_file($package_name, ROOT_PATH.$http['zip_jie_path']);


        $this->update_progress("50%");
        //升级mysql
        if (file_exists(ROOT_PATH . "/update.sql")) {
            $result = $this->database_operation(ROOT_PATH . "/update.sql");
            unlink(ROOT_PATH . "/update.sql");
            if (!$result['status']) {
                echo json_encode($result);
                die;
            }
        }
        $this->update_progress("70%");


        //把解压的升级包清除
//        exec("rm -rf $upload_dir/$package_name ");

        $this->update_progress("100%");
        //更新系统的版本号了
        //更新php的版本号了(应该跟svn／git的版本号一致)
        //更新数据库的版本号了(应该跟svn／git的版本号一致)


        file_put_contents(ROOT_PATH . 'public' . DS . 'version', "uucms for v" . $this->to_lv);

        return true;
    }

    /**
     * 比较代码版本
     * @return [type] [description]
     */
    private function compare_version($version_num = '1.0.0')
    {

        return version_compare($version_num, $this->sys_version_num, '>');
    }

    /**
     * 备份代码
     */
    private function backup_code()
    {

        $zipnam = $this->backup_dir . "/" . date('YmdHis') . '_' . $this->sys_version_num . '.zip';
        $zip = new \ZipArchive();
        $res = $zip->open($zipnam, \ZipArchive::OVERWRITE | \ZipArchive::CREATE);
        if ($res) {
            Tool::zip_ya_dir(APP_PATH, $zip);
            $zip->close();
        }

        return true;
    }

    /**
     * 数据库操作
     */
    public function database_operation($file)
    {
        $sql = file_get_contents($file);


        $host = config('database.hostname');
        $port = config('database.hostport');
        $mysqlUserName = config('database.username');
        $mysqlPassword = config('database.password');
        $mysqlDatabase = config('database.database');
// 连接数据库
        $link = @new \mysqli("{$host}:{$port}", $mysqlUserName, $mysqlPassword);

// 获取错误信息
        $error = $link->connect_error;

        if (!is_null($error)) {
// 转义防止和alert中的引号冲突
            $error = addslashes($error);
            die("<script>alert('数据库链接失败:$error');history.go(-1)</script>");
        }
// 设置字符集
        $link->query("SET NAMES 'utf8'");
        $link->server_info > 5.0 or die("<script>alert('请将您的mysql升级到5.0以上');history.go(-1)</script>");
// 创建数据库并选中
        if (!$link->select_db($mysqlDatabase)) {
            $create_sql = 'CREATE DATAbase IF NOT EXISTS ' . $mysqlDatabase . ' DEFAULT CHARACTER SET utf8;';
            $link->query($create_sql) or die('创建数据库失败');
            $link->select_db($mysqlDatabase);
        }
        $sqlArr = explode(';', $sql);
        foreach ($sqlArr as $key => $val) {
            if ($val) {
                $link->query($val);
            }
        }

        return ["status" => 1, "msg" => "数据库操作OK"];
    }

    /**
     * 返回系统升级的进度
     */
    public function update_progress($progress)
    {
        exec(" echo '" . $progress . "' > $this->progress_log ");
    }

    /**
     * 记录日志
     */
    public function save_log($msg, $action = "update")
    {
        $msg .= date("Y-m-d H:i:s") . ":" . $msg . "\n";
        if ($action == "update") {
            exec(" echo '" . $msg . "' >>  $this->update_log ");
        } else {
            exec(" echo '" . $msg . "' >>  $this->return_log ");
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
}