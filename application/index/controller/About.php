<?php


namespace app\index\controller;

use think\Request;

class About extends Basehome
{
    public $uucms;
    public function _init(Request $request = null)
    {
        $this->m = strtolower(array_reverse(explode("\\", __CLASS__))[0]);
        $this->uucms = new \app\data\controller\Only();
    }

    public function link()
    {
        $UUCMS = $this->uucms->index($this, __FUNCTION__, __CLASS__);

        $this->assign('thiss', $UUCMS['this_mod']);
        $this->assign('data', $UUCMS['data']);
        return $this->staticFetch($this->staticStatus);
    }

    public function echo()
    {
        $UUCMS = $this->uucms->index($this, __FUNCTION__, __CLASS__);

        $this->assign('thiss', $UUCMS['this_mod']);
        $this->assign('data', $UUCMS['data']);
        return $this->staticFetch($this->staticStatus);
    }

    public function info()
    {
        $UUCMS = $this->uucms->index($this, __FUNCTION__, __CLASS__);

        $this->assign('thiss', $UUCMS['this_mod']);
        $this->assign('data', $UUCMS['data']);
        return $this->staticFetch($this->staticStatus);
    }

    public function map()
    {
        $UUCMS = $this->uucms->index($this, __FUNCTION__, __CLASS__);
        $this->assign('thiss', $UUCMS['this_mod']);
        $this->assign('data', $UUCMS['data']);
        return $this->staticFetch($this->staticStatus);
    }
}