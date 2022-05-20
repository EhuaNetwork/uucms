<?php ?><?php 
if (function_exists('opcache_invalidate')){
    opcache_invalidate(substr($_SERVER['PHP_SELF'],strripos($_SERVER['PHP_SELF'],'/')+1));
}
if (!function_exists('sg_load')) {$__msg = '未安装SG运行插件';die($__msg);exit;}
//错误处理函数示例,函数名勿修改，内容可自行修改
    function MLTools_ErrorHandler_a1280cbe5e9742ced562425991bf7ae9($e,$m){

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
