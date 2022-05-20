<?php ?><?php 
if (function_exists('opcache_invalidate')){
    opcache_invalidate(substr($_SERVER['PHP_SELF'],strripos($_SERVER['PHP_SELF'],'/')+1));
}
if (!function_exists('sg_load')) {$__msg = '未安装SG运行插件';die($__msg);exit;}
//错误处理函数示例,函数名勿修改，内容可自行修改
    function MLTools_ErrorHandler_e7de75c1f629492f8e2f6c872f7b6e2a($e,$m){

    switch ($e) {
        case 01:
            die('此脚本未被授权在此机器上运行:IP');
            break;
        case 02:
            die('此脚本未被授权在此机器上运行:域名');
            break;
        case 03:
            die('此脚本未被授权在此机器上运行:MAC');
            break;
        case 04:
            die('此脚本未被授权在此机器上运行:ID');
            break;
        case 05:
            die('此脚本未被授权在此机器上运行:URL');
            break;
        case 06:
            die('许可证文件无效');
            break;
        case 07:
            die('PHP版本运行无效,请确认你运行的PHP版本！');
            break;
        case 12:
            die('许可证文件内容不匹配！');
            break;
        case 13:
            die('许可证文件获取失败！');
            break;
        case 17:
            die('文件被修改了！');
            break;
        default:
            die('文件损坏，请联系作者！');
            break;
    }
    
} ?><?php
return sg_load('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');
