<?php

namespace app\index\controller;
use app\data\controller\Basetdk;
use think\Db;
use app\data\mod\Article;
use think\Request;

class Index extends Basehome
{
    public function _init(Request $request = null)
    {
        $this->m = strtolower(array_reverse(explode("\\", __CLASS__))[0]);
    }

    public function index()
    {
        Basetdk::getLitsTitle($this, $this->m, __FUNCTION__, null);


        $banner = db('cms_banner')->select();
        $this->assign('banner', $banner);


        $case1 = Article::getmodlist(3, [0, 3]);
        $this->assign('case1', $case1);


        $article1 = Article::getmodlist(4, [0, 3]);
        $this->assign('article1', $article1);


        return $this->staticFetch($this->staticStatus);
    }


}
