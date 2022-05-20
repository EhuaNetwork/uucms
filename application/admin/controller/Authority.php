<?php


namespace app\admin\controller;


use Ehua\Bt\Curl\Post;
use Ehua\Tool\Tool;
use GuzzleHttp\Client;
use think\Config;
use function GuzzleHttp\json_encode;

/**
 * 官方对接插件
 * Class Authority
 * User: Ehua
 * Alter: 2022/2/12 13:41
 * @package app\pulg\controller
 */
class Authority extends Common
{

    public function index()
    {
        $post = new Post();
        $url = UUCMS_SERVER . 'admin/mine/index';
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

    public function create()
    {

    }

    /**
     * 下载远程服务器插件
     * @param $i
     * @return void
     */
    public function download($i)
    {

        $urlS = UUCMS_SERVER . 'admin/Authority/ret_paths/i/' . $i;//文件验证地址

        $post = new Post();
        $r = $post->http($urlS);
     

        $r = json_decode($r, true);
        if ($r['code'] == 200) {
            $zipPath = ROOT_PATH . 'package' . DS . 'pulg' . DS . $r['data']['pulg'] . '.zip';

            $url = UUCMS_SERVER . 'admin/Authority/ret_path/i/' . $i;//直连下载地址
            if (file_put_contents($zipPath, file_get_contents($url))) {

                $zipinfo = Tool::zip_info($zipPath, 'config.json');
                $zipinfo = json_decode($zipinfo, true);
                $data = [
                    'pulg_name' => $zipinfo['pulg_name'],
                    'author' => $zipinfo['author'],
                    'describe' => $zipinfo['describe'],
                    'lavel' => $zipinfo['lavel'],
                    'route' => implode('|', $zipinfo['route']),
                    'share' => 0,
                    'pulg' => $r['data']['pulg'],
                    'path' => DS . 'package' . DS . 'pulg' . DS . $r['data']['pulg'] . '.zip',
                    'create_time' => date('Y-m-d H:i:s', time())
                ];
                db('cms_pulg')->insert($data);

                $this->success('下载完成');
            } else {
                $this->error('下载失败');
            }
        } else {
            $this->error('资源失效');
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
        $r = db('cms_pulg')->where('id', $i)->where('share', 1)->find();
        if (!$r) {
            $this->error('资源失效');
        } else {
            $this->downloads(ROOT_PATH . $r['path']);
        }
    }

    public function ret_paths($i)
    {
        $r = db('cms_pulg')->where('id', $i)->where('share', 1)->find();
        if (!$r) {
            return json(['code' => 500, 'data' => [], 'msg' => '资源失效']);
        } else {
            return json(['code' => 200, 'data' => $r, 'msg' => 'OK']);
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