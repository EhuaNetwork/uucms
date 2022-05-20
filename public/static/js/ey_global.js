
// 首页、列表页等加入购物车
function ShopAddCart1625194556(aid, spec_value_id, num, rootDir) {
    rootDir = rootDir ? rootDir : '';
    $.ajax({
        url : rootDir + '/index.php?m=user&c=Shop&a=shop_add_cart&_ajax=1',
        data: {aid: aid, num: num, spec_value_id: spec_value_id},
        type:'post',
        dataType:'json',
        success:function(res){
            if (1 == res.code) {
                window.location.href = res.url;
            } else {
                if (-1 == res.data.code) {
                    layer.msg(res.msg, {time: time});
                } else {
                    // 去登陆
                    window.location.href = res.url;
                }
            }
        }
    });
}

/**
 * 设置cookie
 * @param {[type]} name  [description]
 * @param {[type]} value [description]
 * @param {[type]} time  [description]
 */
function ey_setCookies(name, value, time)
{
    var cookieString = name + "=" + escape(value) + ";";
    if (time != 0) {
        var Times = new Date();
        Times.setTime(Times.getTime() + time);
        cookieString += "expires="+Times.toGMTString()+";"
    }
    document.cookie = cookieString+"path=/";
}

// 读取 cookie
function getCookie(c_name)
{
    if (document.cookie.length>0)
    {
        c_start = document.cookie.indexOf(c_name + "=")
        if (c_start!=-1)
        {
            c_start=c_start + c_name.length+1
            c_end=document.cookie.indexOf(";",c_start)
            if (c_end==-1) c_end=document.cookie.length
            return unescape(document.cookie.substring(c_start,c_end))
        }
    }
    return "";
}

function ey_getCookie(c_name)
{
    return getCookie(c_name);
}

function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]);
    return null;
}

// 检测手机端的标识
function ey_isMobile()
{
    var is_mobile = navigator.userAgent.toLowerCase().match(/(ipod|iphone|android|coolpad|mmp|smartphone|midp|wap|xoom|symbian|j2me|blackberry|wince)/i) != null;
    // 判断手机端并且跳转
    if (is_mobile) {
        return true;
    } else {
        return false;
    }
}

function layerLogin(gourl) {
    if (ey_isMobile()) {
        var url = '//'+window.location.host+'/login';
        window.location.href = url;
    } else {
        var url = '//'+window.location.host+'/login?iframe=1';
        if (gourl) {
            url += '&referurl='+encodeURIComponent(gourl);
        }
        //iframe窗
        layer.open({
            type: 2,
            id: 'iframe_userLogin',
            title: false,//'会员登录',
            fixed: true, //不固定
            skin: 'layui-layer-iframe2021',
            shadeClose: false,
            shade: 0.3,
            maxmin: false, //开启最大化最小化按钮
            area: ['410px','450px'],
            content: url
        });
    }
}

function winopenQQLogin(gourl)
{
    var url = '//'+window.location.host+'/index.php?m=plugins&c=QqLogin&a=login';
    if (gourl) {
        url += '&referurl='+encodeURIComponent(gourl);
    }
    var userAgent = navigator.userAgent;
    var desc = navigator.mimeTypes['application/x-shockwave-flash'];//.description.toLowerCase();
    if (userAgent.indexOf("Chrome") > -1 && !desc) {
        window.location.href = url;
    }else{
        url += '&iframe=1';
        var b = 720, c = 450;
        window.open(url, '账户关联', "width=" + b + ",height=" + c + ",top=" + ((window.screen.availHeight - 30 - c) / 2) + ",left=" + ((window.screen.availWidth - 10 - b) / 2) + ",menubar=0,scrollbars=1,resizable=1,status=1,titlebar=0,toolbar=0,location=1");
    }
}

function winopenWxLogin(gourl)
{
    var url = '//'+window.location.host+'/index.php?m=plugins&c=WxLogin&a=login';
    if (gourl) {
        url += '&referurl='+encodeURIComponent(gourl);
    }
    var userAgent = navigator.userAgent;
    var desc = navigator.mimeTypes['application/x-shockwave-flash'];//.description.toLowerCase();
    if (userAgent.indexOf("Chrome") > -1 && !desc) {
        window.location.href = url;
    }else{
        url += '&iframe=1';
        var b = 720, c = 550;
        window.open(url, '账户关联', "width=" + b + ",height=" + c + ",top=" + ((window.screen.availHeight - 30 - c) / 2) + ",left=" + ((window.screen.availWidth - 10 - b) / 2) + ",menubar=0,scrollbars=1,resizable=1,status=1,titlebar=0,toolbar=0,location=1");
    }
}