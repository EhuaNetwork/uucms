<?php


namespace app\index\controller;


use app\data\controller\Basetdk;
use app\data\mod\Article;
use think\Controller;
use think\Db;


class Basehome extends Controller
{
    public $staticStatus = false;
    public $M;
    public $C;
    public $A;
    public $system;

    public function _initialize()
    {

        \app\data\controller\Basehome::init($this, request());


        //获取公共数据
        $this->setCommon();

        $this->_init();

    }
    public function route($t){

        $temp = explode('-', $t);
        if ($temp[0]) {

            switch ($temp[0]) {
                case 'show':
                    $m = db('cms_mod')->where('id',$temp[1])->find();
                    if(!$m){
                        $this->request->module('index');
                        $this->request->controller('index');
                        $this->request->action('index');
                        $data = new \app\index\controller\Index();
                        return   $data->index();
                    }
                    try{
                        eval("\$data = new \\app\\" . $m['m'] . "\\controller\\" . ucfirst($m['c']) . "();");
                        eval("\$data->content(".$temp[2].");");
                    } catch (\think\exception\TemplateNotFoundException $exception) {

                        $HTML = $this->fetch($m['m'] . '@' . $m['c'] . DS . 'content',['i'=>$temp[2]]);//获得页面HTML代码
                        $HTML = str_replace('="/index/', '="/', $HTML);
                        $HTML = str_replace('="/index/index.html', '="/', $HTML);
                        echo $HTML;die;
                    } catch (\Exception $exception) {
                        dd($exception);
                    }
                    break;
                case 'list':
                    try{
                        $m = db('cms_mod')->where('id',$temp[1])->find();

                        if(!$m){

                            $this->request->module('index');
                            $this->request->controller('index');
                            $this->request->action('index');
                            $data = new \app\index\controller\Index();
                            return   $data->index();
                        }else{

                            eval("\$data = new \\app\\" . $m['m'] . "\\controller\\" . ucfirst($m['c']) . "();");
                            eval("\$data->" . $m['a'] . "();");
                        }


                    } catch (\think\exception\TemplateNotFoundException $exception) {

                        $HTML = $this->fetch($m['m'] . '@' . $m['c'] . DS . $m['a'],['t'=>$temp[2]]);//获得页面HTML代码
                        $HTML = str_replace('="/index/', '="/', $HTML);
                        $HTML = str_replace('="/index/index.html', '="/', $HTML);
                        echo $HTML;die;
                    } catch (\Exception $exception) {
                        dd($exception);
                    }


                    break;
                default:



                    break;
            }
        }

    }
    public function _init()
    {

        //注册加载方法 此处不写代码
    }

    public function setCommon()
    {
        //导航
        $nav = get_nav();
        $this->assign('nav', $nav);

        $cse1 =  Article::getmodlist(4, [0, 6]);
        $this->assign('cse1', $cse1);
        //友链
        $links = db('cms_link')->where('status', 1)->select();
        $this->assign('links', $links);

        //系统配置
        $this->system = get_system();
        $this->system['web_title'] = Basetdk::getTitle($this, $this->C, $this->A);
        $this->assign('system', $this->system);
    }


    /**
     * 模板输出重写方法
     * @access protected
     * @param boolean $isstatic 是否保存为静态文件
     * @param string $template 模板文件名
     * @param array $vars 模板输出变量
     * @param array $replace 模板替换
     * @param array $config 模板参数
     * @return mixed
     */
    public function staticFetch($isstatic = false, $template = '', $vars = [], $replace = [], $config = [])
    {
        $HTML = $this->fetch($template, $vars, $replace, $config);//获得页面HTML代码
        if ($isstatic == "true") {//判断是否需要保存为静态页
            //多参数目录递归版本
            $new_file = request()->url();
            $new_file = explode('/', $new_file);
            $thisAction = array_pop($new_file);
            array_shift($new_file);
            $new_file = implode('/', $new_file);
            if (empty($new_file)) {
                $thisModule = strtolower(request()->module());//获取模块
                $thisController = strtolower(request()->controller());//获取控制器
                $thisAction = strtolower(request()->action());//获取方法
                if ($thisModule == 'index' || $thisController == 'index' || $thisAction == 'index') {
                    file_put_contents('index.html', $HTML);//生成静态页
                }

                $new_file = "{$thisModule}/{$thisController}";
                if (!file_exists($new_file)) {
                    //检查是否有该文件夹，如果没有就创建，并给予最高权限
                    mkdir($new_file, 0777, true);
                }
                $new_file .= "/{$thisAction}." . config('default_return_type');

                if (substr($new_file, -5) != '.html') {
                    $new_file .= '.html';
                }

                file_put_contents($new_file, $HTML);//生成静态页
            } else {
                if (!file_exists($new_file)) {
                    //检查是否有该文件夹，如果没有就创建，并给予最高权限
                    mkdir($new_file, 0777, true);
                }
                $new_file .= "/{$thisAction}";

                if (substr($new_file, -1) == '/') {
                    $new_file .= 'index.html';
                } else {
                    if (substr($new_file, -5) != '.html') {
                        $new_file .= '.html';
                    }
                }

                file_put_contents($new_file, $HTML);//生成静态页
            }
        }
        return $HTML;
    }


}
