<?php


namespace app\api\controller;


use Ehua\Tool\Tool;
use think\response\Json;

class Upload
{
    //layui
    public function img()
    {

        $file = request()->file('file');
        if (empty($file)) {
            return json(['code' => -1, 'msg' => '未获取到文件信息', 'data' => []]);
        }

        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if ($info) {

            $name_path = str_replace('\\', "/", $info->getSaveName());

            return json(['code' => 0, 'msg' => '上传成功', 'data' => ['src' => '/uploads/' . $name_path]]);
        } else {
            return json(['code' => -1, 'msg' => '上传失败', 'data' => $info->getError()]);
        }
    }

    //插件上传
    public function pulg_zip()
    {

        $file = request()->file('file');
        if (empty($file)) {
            return json(['code' => -1, 'msg' => '未获取到文件信息', 'data' => []]);
        }
//        $info = $file->move(ROOT_PATH.'pulg'.DS);

        $zipPath = ROOT_PATH . 'package' . DS . 'pulg' . DS . $file->getInfo('name');

        if (copy($file->getInfo('tmp_name'), $zipPath)) {

            $zipinfo = Tool::zip_info($zipPath, 'config.json');
            $zipinfo = json_decode($zipinfo, true);
 
            $data = [
                'pulg_name' => $zipinfo['pulg_name'],
                'author' => $zipinfo['author'],
                'describe' => $zipinfo['describe'],
                'lavel' => $zipinfo['lavel'],
                'route' => implode('|', $zipinfo['route']),
                'share' => 0,
                'pulg' => explode('.', $file->getInfo('name'))[0],
                'path' => DS . 'package' . DS . 'pulg' . DS . $file->getInfo('name'),
                'create_time' => date('Y-m-d H:i:s', time())
            ];
            db('cms_pulg')->insert($data);
            return json(['code' => 0, 'msg' => '上传成功', 'data' => '']);
        } else {
            return json(['code' => -1, 'msg' => '上传失败', 'data' => '']);
        }
    }

    //wangeditor
    public function wangeditor()
    {

        $file = request()->file();
        if (empty($file)) {
            return json(['code' => -1, 'msg' => '未获取到文件信息', 'data' => []]);
        }


        foreach ($file as $f) {
            $info = $f->move(ROOT_PATH . 'public' . DS . 'uploads');
            $name_path = str_replace('\\', "/", $info->getSaveName());

            $data[] = ['url' => '/uploads/' . $info->getSaveName(), 'alt' => '', 'href' => '/uploads/' . $name_path];
        }


        if ($info) {
            return json(['errno' => 0, 'data' => $data]);
        } else {
            return json(['errno' => -1, 'fail' => $info->getError()]);
        }
    }


    public function wangeditor3()
    {
        $files = request()->file('file');

        //上传回调error为0
        if (empty($files)) {
            $result["error"] = "1";
            $result['data'] = '';
        } else {
            if (is_array($files)) {
                foreach ($files as $file) {
                    // 移动到框架应用根目录/public/uploads/ 目录下


                    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/editor');
                    if ($info) {
                        $name_path = $info->getSaveName();
                        //成功上传后 获取上传信息
                        $name_path = str_replace('\\', "/", $info->getSaveName());
                        $path[] = "/uploads/editor/" . $name_path;
                    } else {
                        // 上传失败获取错误信息
//                    $result["code"] = "2";
//                    $result["errno"] = '1';
//                    $result['data'] = '';
                    }
                }
            } else {
                $info = $files->move(ROOT_PATH . 'public' . DS . 'uploads/editor');
                if ($info) {
                    $name_path = $info->getSaveName();
                    //成功上传后 获取上传信息
                    $name_path = str_replace('\\', "/", $info->getSaveName());
                    $path = "/uploads/editor/" . $name_path;
                }
            }
            $result["error"] = '0';
            $result["errno"] = '0';
            $result['data'] = $path;
        }
        exit(json_encode($result));
    }

    public function wangeditor3_video()
    {
        $file = request()->file('file');
        //上传回调error为0
        if (empty($file)) {
            $result["error"] = "1";
            $result['data'] = '';
        } else {
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/editor');
            if ($info) {
                $name_path = $info->getSaveName();
                //成功上传后 获取上传信息
                $name_path = str_replace('\\', "/", $info->getSaveName());
                $result["error"] = '0';
                $result["errno"] = '0';

                $result['data'] = "/uploads/editor/" . $name_path;
            } else {
                // 上传失败获取错误信息
                $result["errno"] = '1';
                $result["code"] = "2";
                $result['data'] = '';
            }
        }
        exit(json_encode($result));
    }

    public function nkeditor(){
        function alert($msg) {
        	$json = new JsonResult(JsonResult::CODE_FAIL, $msg);
        	$json->output();
        }
        /**
         * 全局函数
         * @author yangjian<yangjian102621@gmail.com>
         */

// 文件上传的根路径
        define('BASE_PATH', ROOT_PATH."public/uploads/");
// 文件上传路径前缀
        define('UPLOAD_PREFIX', date('Ym').'/'.date('d').'/');
// 文件上传的根 url
        define('BASE_URL', dirname(dirname(dirname($_SERVER['PHP_SELF'])))."/uploads/");

        /**
         * 创建多级目录
         * @param $dir
         */
        // function mkdirs($path) {
        //     $files = preg_split('/[\/|\\\]/s', $path);
        //     $_dir = '';
        //     foreach ($files as $value) {
        //         $_dir .= $value.DIRECTORY_SEPARATOR;
        //         if ( !file_exists($_dir) ) {
        //             mkdir($_dir);
        //         }
        //     }
        // }

        /**
         * 获取文件后缀名
         * @param $filename
         * @return string
         */
        function getFileExt($filename) {
            $temp_arr = explode(".", $filename);
            $file_ext = array_pop($temp_arr);
            return strtolower(trim($file_ext));
        }

        /**
         * 显示图片
         * @param $image
         * @param $img_url
         */
        function show_image($image, $img_url) {

            $info = pathinfo($img_url);
            switch ( strtolower($info["extension"]) ) {
                case "jpg":
                case "jpeg":
                    header('content-type:image/jpg;');
                    imagejpeg($image);
                    break;

                case "gif":
                    header('content-type:image/gif;');
                    imagegif($image);
                    break;

                case "png":
                    header('content-type:image/png;');
                    imagepng($image);
                    break;

                default:
                    header('content-type:image/wbmp;');
                    image2wbmp($image);
            }

        }

        /**
         * 生成新的文件名
         * @param $file
         * @return string
         */
        function genNewFilename($file) {
            $extesion = getFileExt($file);
            return date("YmdHis") . '_' . rand(10000, 99999) . '.' . $extesion;
        }

        /**
         * 清空目录
         * @param $dirName
         * @return bool
         */
        function deldir($dirName) {
            //节省资源，每天清理一次
            $file = "cache.tmp";
            $t = @file_get_contents($file);
            $now = time();
            if ($now - intval($t) < 60*60*24) {
                return false;
            }
            file_put_contents($file, $now);

            if(file_exists($dirName) && $handle=opendir($dirName)){
                while(false!==($item = readdir($handle))){
                    if($item!= "." && $item != ".."){
                        if(file_exists($dirName.'/'.$item) && is_dir($dirName.'/'.$item)){
                            delFile($dirName.'/'.$item);
                        }else{
                            @unlink($dirName.'/'.$item);
                        }
                    }
                }
                closedir( $handle);
            }
        }



        $fileType = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
//文件保存目录路径

        $basePath = BASE_PATH."{$fileType}/".UPLOAD_PREFIX;
//文件保存目录URL
        $baseUrl = BASE_URL . "{$fileType}/".UPLOAD_PREFIX;
        $baseUrl = str_replace('\\', "", $baseUrl);
        $baseUrl = str_replace('//', "/", $baseUrl);
//定义允许上传的文件扩展名
        $allowExtesions = array(
            'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
            'flash' => array('swf', 'flv'),
            'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
            'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
        );
//最大文件大小 2MB
        $maxSize = 2*1024*1024;

        if (!file_exists($basePath)) {
       
            // mkdirs($basePath);

            \Ehua\Tool\Tool::dir_create($basePath);
        }
//PHP上传失败
        if (!empty($_FILES['imgFile']['error'])) {
            switch($_FILES['imgFile']['error']){
                case '1':
                    $error = '超过php.ini允许的大小。';
                    break;
                case '2':
                    $error = '超过表单允许的大小。';
                    break;
                case '3':
                    $error = '图片只有部分被上传。';
                    break;
                case '4':
                    $error = '请选择图片。';
                    break;
                case '6':
                    $error = '找不到临时目录。';
                    break;
                case '7':
                    $error = '写文件到硬盘出错。';
                    break;
                case '8':
                    $error = 'File upload stopped by extension。';
                    break;
                case '999':
                default:
                    $error = '未知错误。';
            }
            alert($error);
        }
     
//base64 文件上传
        $base64 = trim(@$_POST['base64']);
        if ($base64) {
            $imgData = $_POST['img_base64_data'];

            $json = new JsonResult();
            if ($imgData && preg_match('/^(data:\s*image\/(\w+);base64,)/', $imgData, $match)){
                $type = $match[2];
                $filename = date("YmdHis") . '_' . rand(10000, 99999) . '.png';
                if (file_put_contents($basePath.$filename, base64_decode(str_replace($match[1], '', $imgData)))){
                    $json->setCode(JsonResult::CODE_SUCCESS);
                    $json->setData(array('url' => $baseUrl.$filename));
                    $json->output();
                }
            }
            $json->setCode(JsonResult::CODE_FAIL);
            $json->setMessage("涂鸦保存失败.");
            $json->output();
        }

// input 文件上传
        if (empty($_FILES) == false) {
            //原文件名
            $filename = $_FILES['imgFile']['name'];
            //服务器上临时文件名
            $tmpName = $_FILES['imgFile']['tmp_name'];
            //文件大小
            $filesize = $_FILES['imgFile']['size'];
            //检查文件名
            if (!$filename) {
                alert("请选择文件。");
            }
            //检查目录
            if (@is_dir($basePath) === false) {
                alert("上传目录不存在。");
            }
            //检查目录写权限
            if (@is_writable($basePath) === false) {
                alert("上传目录没有写权限。");
            }
            //检查是否已上传
            if (@is_uploaded_file($tmpName) === false) {
                alert("上传失败。");
            }
            //检查文件大小
            if ($filesize > $maxSize) {
                alert("上传文件大小超过限制。");
            }

            //获得文件扩展名
            $extesion = getFileExt($filename);
            //检查扩展名
            if (in_array($extesion, $allowExtesions[$fileType]) === false) {
                alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $allowExtesions[$fileType]) . "格式。");
            }
            //新文件名
            $newFileName = genNewFilename($filename);
            //移动文件
            $filePath = $basePath . $newFileName;
            if (move_uploaded_file($tmpName, $filePath) === false) {
                alert("上传文件失败。");
            }
            @chmod($filePath, 0644);
            $fileUrl = $baseUrl . $newFileName;

            $json = new JsonResult(JsonResult::CODE_SUCCESS, "上传成功");
            $json->setData(array('url' => $fileUrl));

//            //保存文件地址到数据库
//            $db = new SimpleDB($fileType);
//            //过滤掉非图片文件
//            if ($fileType == "image") {
//                $size = getimagesize($filePath);
//            }
//            $data = [
//                "thumbURL" => $fileUrl,
//                "oriURL" => $fileUrl,
//                "filesize" => $filesize,
//                "width" => intval($size[0]),
//                "height" => intval($size[1])
//            ];
//            $db->putLine($data);

            $json->output();
        }


    }


}



class JsonResult {

    const CODE_SUCCESS = "000";
    const CODE_FAIL = "001";

    /**
     * 数据载体
     * @var array
     */
    private $data;

    /**
     * 列表数据条数
     * @var int
     */
    private $count;

    /**
     * 当前数据页码
     * @var int
     */
    private $page;

    /**
     * 每页显示数据条数
     * @var int
     */
    private $pagesize;

    /**
     * 附带数据
     * @var mixed
     */
    private $extra;
    /**
     * 错误代码
     * @var string
     */
    private $code = self::CODE_SUCCESS;

    /**
     * 状态码信息
     * @var array
     */
    private static $_CODE_STATUS = [
        self::CODE_SUCCESS => '操作成功.',
        self::CODE_FAIL => '系统开了小差.',
    ];

    /**
     * 消息
     * @var string
     */
    private $message;

    /**
     * JsonResult constructor.
     * @param $code
     * @param $message
     */
    public function __construct($code=null, $message=null){
        $this->setCode($code);
        $this->setMessage($message);
    }

    /**
     * 创建 JsonResult 实例, 并输出
     * @param $code
     * @param $message
     * @return JsonResult
     */
    public static function result($code, $message) {
        $result = new self($code, $message);
        $result->output();
    }

    /**
     * 返回一个成功的 result vo
     * @param string $message
     * @return JsonResult
     */
    public static function success($message='操作成功') {
        $result = new self(self::CODE_SUCCESS, $message);
        $result->output();
    }

    /**
     * 返回一个失败的 result vo
     * @param string $message
     * @return JsonResult
     */
    public static function fail($message='系统开了小差') {
        $result = new self(self::CODE_FAIL, $message);
        $result->output();
    }

    /**
     * 返回jsonp数据格式
     * @param $code
     * @param $message
     * @param $callback
     */
    public static function jsonp($code, $message, $callback){
        $result = new self($code, $message);
        die($callback. "(". $result .")");
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return the $message
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message) {
        $this->message = $message;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getPagesize()
    {
        return $this->pagesize;
    }

    /**
     * @param int $pagesize
     */
    public function setPagesize($pagesize)
    {
        $this->pagesize = $pagesize;
    }

    /**
     * @return mixed
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * @param mixed $extra
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;
    }

    /**
     * 判断是否成功
     * @return bool
     */
    public function isSucess() {
        return $this->code == self::CODE_SUCCESS;
    }

    /**
     * 转换字符串
     * @return string
     */
    public function __toString() {
        if ( !$this->getMessage() ) {
            $this->setMessage(self::$_CODE_STATUS[$this->code]);
        }
        return json_encode(array(
            'code'=>$this->getCode(),
            'message'=>$this->getMessage(),
            'count'=>$this->getCount(),
            'page'=>$this->getPage(),
            'pagesize'=>$this->getPagesize(),
            'extra'=>$this->getExtra(),
            'data'=>$this->getData()), JSON_UNESCAPED_UNICODE);
    }

    /**
     * 以json格式输出
     */
    public function output() {
        header('Content-type: application/json;charset=utf-8');
        echo $this;
        die();
    }
}