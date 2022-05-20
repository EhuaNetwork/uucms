<?php


namespace app\pulg\controller;


use Ehua\Tool\Tool;
use GuzzleHttp\Client;
use think\Controller;

class Ecaiji extends Controller
{
    public $guzz;

    public $stream_opts = [
        "ssl" => [
            "verify_peer" => false,
            "verify_peer_name" => false,
        ]
    ];

    /**
     * 入口方法
     * @return \think\response\View
     * User: Ehua
     * Alter: 2022/2/12 17:18
     */
    public function into()
    {
        echo <<<eol
暂无界面，请根据需要自行修改采集脚本后执行init方法
eol;
    }


    /**
     * 初始化入口
     */
    public function init($page = 0)
    {
        if ($page <= 0) {
            die;
        }


        $domain = "http://www.fenglinhuanbao.cn";
        $tt = 93;
        $p = "/a/xinwenzhongxin/list_10_$page.html";


        $this->guzz = new Client($this->stream_opts);
        $res = (string)$this->guzz->get($domain . $p)->getBody();
        $res = str_replace("charset=gb2312", 'charset=utf-8', $res);
        $res = Tool::str_To_Utf8($res);
        $res = \phpQuery::newDocument($res);

        $data = [];
        \phpQuery::selectDocument($res);
        $count = pq('body')->find('.page-container')->eq(0)->find('li');
        $b = pq('body')->find('table')->eq(14)->find('.listA');
        for ($i = 0; $i < (12); $i++) {
//            $data[$i]['name'] = trim($b->eq($i)->text());
            $url = $domain . $b->eq($i)->attr('href');
//            $img = '';
//            $rand = uniqid() . rand(100, 999);
//            $this->makdir();
//            $path = DS . 'uploads' . DS . 'sort' . DS . $rand . '.jpg';
//            file_put_contents(ROOT_PATH . 'public' . $path, file_get_contents($img));
//
//            $data[$i]['img'] = $path;
            $data[$i]['type'] = $tt;

            $cai = $this->getbody($domain, $url);
            $data[$i]['body'] = $cai['body'];
            $data[$i]['create_time'] = $b->eq($i)->parent()->next()->text();
            $data[$i]['name'] = $cai['name'];

            //TODO 重置id
//            preg_match("/\d+/", $url, $data[$i]['id']);
//            $data[$i]['id'] = $data[$i]['id'][0];
            \phpQuery::selectDocument($res);
        }
        db('cms_article')->insertAll($data);
        $page--;
        echo "<script language=\"javascript\" type=\"text/javascript\"> 
window.location.href='/api/caiji/init/page/$page'; 
</script> ";
        die;

    }

    private function getbody($domain, $url)
    {
        try {
            $this->makdir();
            $res = (string)$this->guzz->get($url)->getBody();
            $res = str_replace("charset=gb2312", 'charset=utf-8', $res);
            $res = Tool::str_To_Utf8($res);

            $res = \phpQuery::newDocument($res);
            \phpQuery::selectDocument($res);


            $imgs = pq('table')->eq(4)->find('img');
            $body = pq('table')->eq(4)->html();

            $count = $imgs->count();
            for ($i = 0; $i < $count; $i++) {
                $temp_img = $imgs->eq($i)->attr('src');
                $img = $domain . $temp_img;
                $rand = uniqid() . rand(100, 999);
                $path = DS . 'uploads' . DS . 'sort' . DS . $rand . '.jpg';
                file_put_contents(ROOT_PATH . 'public' . $path, @file_get_contents($img));
                $body = str_replace($temp_img, $path, $body);
            }
            $data['name'] = pq('.bt01')->eq(1)->text();;
//            $data['create_time']=$time[0];
            $data['body'] = preg_replace("/作者：.*? 文章来源：.*? 点击数：\d+ 更新时间：20\d+-\d+-\d+ \d+:\d+:\d+ /", '', $body);

            return $data;

        } catch (\Exception $exception) {
            var_dump($exception);
            dd($url);
        }
    }


    private function makdir()
    {
        if (!file_exists(ROOT_PATH . 'public' . DS . 'uploads' . DS)) {
            mkdir(ROOT_PATH . 'public' . DS . 'uploads' . DS);//这里会返回true
        }
        if (!file_exists(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'sort' . DS)) {
            mkdir(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'sort' . DS);//这里会返回true
        }
    }


}