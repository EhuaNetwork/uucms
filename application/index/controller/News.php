<?php

namespace app\index\controller;

use think\Request;

class News extends Basehome
{
    public $table = 'article';
    public $m;
    public $uucms;

    public function _init(Request $request = null)
    {
        $this->m = strtolower(array_reverse(explode("\\", __CLASS__))[0]);
        $this->uucms = new \app\data\controller\Lists();
    }

    public function index($t = null)
    {
        $Show_num = 12;
        $UUCMS = $this->uucms->index($this, __FUNCTION__, __CLASS__, $t, $Show_num);

        $this->assign('data', $UUCMS['data']);//数据列表
        $this->assign('render', $UUCMS['render']);//分页
        $this->assign('this_type', $UUCMS['this_type']);//子栏目列表
        $this->assign('thiss', $UUCMS['this_mod']);//当前栏目
        return $this->staticFetch($this->staticStatus);

    }

    public function search($k = null)
    {
        return $this->uucms->search($this, __FUNCTION__, __CLASS__, $k);
    }

    public function content($i = null)
    {
        $UUCMS = $this->uucms->content($this, __FUNCTION__, __CLASS__, $i);

        $this->assign('data', $UUCMS['data']);//数据列表
        $this->assign('thiss', $UUCMS['this_mod']);//当前栏目
        $this->assign('this_type', $UUCMS['this_type']);//子栏目列表
        $this->assign('pag', $UUCMS['pag']);//上一页下一页
        return $this->staticFetch($this->staticStatus);

    }
}