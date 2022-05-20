$(function () {
    try {
        var mid = ey_getCookie('users_id');
        if (0 >= mid) {
            var price_1597223814 = $('input[name=price_1597223814]').val();
            if (price_1597223814 == 0) {
                $('#is_show_buy').hide();
            } else {
                $('#is_show_buy').show();
            }
        }
    } catch (e) {
    }
})

function popups_submit(type, url) {
    var mid = ey_getCookie('users_id');
    if (0 < mid) {
        var domainObj = $('#popups_' + type + '_form').find('input[name=new_domain]');
        var domain = domainObj.val();
        if (domain == '') {
            layer.msg('域名不能为空！', {time: 1000});
            $('#popups_' + type + '_form').find('input[name=new_domain]').focus();
            return false;
        }
        layer_loading('正在处理');

        var aid = $('input[name=aid_1597223814]').val();
        var plugins_code = $('input[name=plugins_code_1597223814]').val();
        $.ajax({
            url: url,
            data: {aid: aid, plugins_code: plugins_code, domain: domain},
            type: 'post',
            dataType: 'json',
            success: function (res) {
                if (res.code == 1) {
                    layer.closeAll();
                    layer.msg(res.msg, {icon: 1, time: 1000}, function () {
                        window.location.reload();
                    });
                } else {
                    layer.closeAll();
                    layer.alert(res.msg, {icon: 2, title: false});
                }
            },
            error: function (e) {
                layer.closeAll();
                layer.alert('未知错误，无法继续~', {icon: 2, title: false});
            }
        })
    } else {
        layerLogin();
    }
}

function popups(type, obj) {
    var mid = ey_getCookie('users_id');
    if (0 < mid) {
        if (0 == type) { // 插件版本在v1.4.6以下的收费插件
            var content = $('#popups_' + type).html();
            content = content.replace('#type#', type);
            // if (1 == is_show_repeat_buy_123) {
            // content = content.replace('#is_show_new_domain#', ' style="display:none;" ');
            // } else {
            content = content.replace('#is_show_repeat_buy#', ' style="display:none;" ');
            // }
            layer.open({
                type: 1,
                id: 'popups_layer',
                title: false,
                shadeClose: true,
                content: content,
                success: function () {
                    $('#popups_' + type + '_form').find('input[name=new_domain]').focus();
                }
            });
        } else if (3 == type) { // 插件版本在v1.4.6以下的免费插件
            var content = $('#popups_' + type).html();
            content = content.replace('#type#', type);
            layer.open({
                type: 1,
                title: false,
                shadeClose: true,
                content: content
            });
        } else if (1 == type) { // 插件版本在v1.4.6或以上的免费插件
            layer.open({
                type: 1,
                title: false,
                shadeClose: true,
                content: '请将网站升级到最新版本，在后台云插件库在线安装！'
            });
        } else if (2 == type) { // 插件版本在v1.4.6或以上的收费插件
            var content = $('#popups_' + type).html();
            content = content.replace('#type#', type);
            layer.open({
                type: 1,
                id: 'popups_layer',
                title: false,
                shadeClose: true,
                content: content,
                success: function () {
                    $('#popups_' + type + '_form').find('input[name=new_domain]').focus();
                }
            });
        } else if (5 == type) { // 免费下载
            var downurl = $(obj).attr('data-href');
            window.location.href = downurl;
        }
    } else {
        layerLogin();
    }
}

function buySubmit() {
    var mid = ey_getCookie('users_id');
    if (0 < mid) {
        var content = '';
        content += '<div style="margin-top:20px;">';
        content += '<a href="javascript:void(0);" onclick="layer.closeAll();parent.formSubmit(\'alipay\');" style="float: left;">';
        content += ' <img src="/skin/images/alipay.png" width="150" height="50" alt="支付宝支付" />';
        content += '</a>';
        content += '<a href="javascript:void(0);" onclick="layer.closeAll();parent.formSubmit(\'wechat\');" style="float: right;">';
        content += ' <img src="/skin/images/weipay.png" width="150" height="50" alt="微信支付" />';
        content += '</a>';
        content += '</div>';

        layer.open({
            type: 1,
            title: '选择支付方式',
            shadeClose: false,
            maxmin: false, //开启最大化最小化按钮
            skin: 'WeChatScanCode_20191120',
            area: ['320px', '150px'],
            content: content
        });
    } else {
        layerLogin();
    }
}

function formSubmit(payMethod) {
    var formid = 'postForm';
    $('#' + formid).append("<input type='hidden' name='pay_method' value='" + payMethod + "'>");
    $.ajax({
        url: $('#' + formid).attr('action'),
        data: $('#' + formid).serialize(),
        type: 'post',
        dataType: 'json',
        success: function (res) {
            if (0 == res.code) {
                if (res.url) {
                    layerLogin();
                } else {
                    layer.alert(res.msg, {icon: 2, title: false});
                }
            } else if (1 == res.code) {
                if ('alipay' == payMethod) {
                    // 支付宝支付
                    layer_loading('正在支付');
                    layer.confirm('支付成功即可安装该插件！', {
                        btn: ['支付成功', '支付失败'], //按钮
                        cancel: function (index, layero) {
                            window.location.reload();
                            return false;
                        }
                    }, function () {
                        window.location.reload();
                    }, function (index) {
                        layer.closeAll(index);
                    });
                    PayPolling = window.setInterval(function () {
                        OrderPayPolling(res.data);
                    }, 2000);
                    window.open(res.data.alipay_url, '_blank ');
                } else if ('wechat' == payMethod) {
                    // 微信支付
                    AlertPayImg(res.data);
                } else {
                    layer.msg('请选择支付方式', {time: 2000});
                }
            }
        }
    });
}

// 举报/纠错
function reportaddsave(obj) {
    var url = $(obj).attr('data-href');
    //iframe窗
    layer.open({
        type: 2,
        id: 'iframe_reportaddsave',
        title: false,
        fixed: true, //不固定
        shadeClose: false,
        shade: 0.3,
        offset: '180px',
        maxmin: false, //开启最大化最小化按钮
        area: ['800px', '500px'],
        content: url
    });
}

function changeFocus(id) {
    $('#layer_top').find('.c_em').removeClass('cur');
    $('#layer_top em.'+id).addClass('cur');
    document.getElementById(id).scrollIntoView()
}

/**
 * //图片放大镜---------------------------------------------------
 */
window.onload = function () {
    //需求：鼠标放到小盒子上，让大盒子里面的图片和我们同步等比例移动。
    //技术点：οnmοuseenter==onmouseover 第一个不冒泡
    //技术点：οnmοuseleave==onmouseout  第一个不冒泡
    //步骤：
    //1.鼠标放上去显示盒子，移开隐藏盒子。
    //2.老三步和新五步（黄盒子跟随移动）
    //3.右侧的大图片，等比例移动。

    //0.获取相关元素
    var box = document.getElementsByClassName("img_box")[0];
    var small = box.firstElementChild || box.firstChild;
    var big = box.children[1];
    var mask = small.children[1];
    var bigImg = big.children[0];

    //1.鼠标放上去显示盒子，移开隐藏盒子。(为小盒子绑定事件)
    small.onmouseenter = function () {
        //封装好方法调用：显示元素
        show(mask);
        show(big);
    }
    small.onmouseleave = function () {
        //封装好方法调用：隐藏元素
        hide(mask);
        hide(big);
    }

    //2.老三步和新五步（黄盒子跟随移动）
    //绑定的事件是onmousemove，而事件源是small(只要在小盒子上移动1像素，黄盒子也要跟随)
    small.onmousemove = function (event) {
        //新五步
        event = event || window.event;

        //想要移动黄盒子，必须要知道鼠标在small小图中的位置。
        var pagex = event.pageX || scroll().left + event.clientX;
        var pagey = event.pageY || scroll().top + event.clientY;

        //x：mask的left值，y：mask的top值。
        var x = pagex - box.offsetLeft - mask.offsetWidth / 2; //除以2，可以保证鼠标mask的中间
        var y = pagey - box.offsetTop - mask.offsetHeight / 2;

        //限制换盒子的范围
        //left取值为大于0，小盒子的宽-mask的宽。
        if (x < 0) {
            x = 0;
        }
        if (x > small.offsetWidth - mask.offsetWidth) {
            x = small.offsetWidth - mask.offsetWidth;
        }
        //top同理。
        if (y < 0) {
            y = 0;
        }
        if (y > small.offsetHeight - mask.offsetHeight) {
            y = small.offsetHeight - mask.offsetHeight;
        }

        //移动黄盒子
        console.log(small.offsetHeight);
        mask.style.left = x + "px";
        mask.style.top = y + "px";

        //3.右侧的大图片，等比例移动。
        //如何移动大图片？等比例移动。
        //    大图片/大盒子 = 小图片/mask盒子
        //    大图片走的距离/mask走的距离 = （大图片-大盒子）/（小图片-黄盒子）
        //var bili = (bigImg.offsetWidth-big.offsetWidth)/(small.offsetWidth-mask.offsetWidth);

        //大图片走的距离/mask盒子都的距离 = 大图片/小图片
        var bili = bigImg.offsetWidth / small.offsetWidth;

        var xx = bili * x;  //知道比例，就可以移动大图片了
        var yy = bili * y;

        bigImg.style.marginTop = -yy + "px";
        bigImg.style.marginLeft = -xx + "px";
    }
}

//显示和隐藏
function show(ele) {
    ele.style.display = "block";
}

function hide(ele) {
    ele.style.display = "none";
}

function scroll() {  // 开始封装自己的scrollTop
    if (window.pageYOffset != null) {  // ie9+ 高版本浏览器
        // 因为 window.pageYOffset 默认的是  0  所以这里需要判断
        return {
            left: window.pageXOffset,
            top: window.pageYOffset
        }
    }
    else if (document.compatMode === "CSS1Compat") {    // 标准浏览器   来判断有没有声明DTD
        return {
            left: document.documentElement.scrollLeft,
            top: document.documentElement.scrollTop
        }
    }
    return {   // 未声明 DTD
        left: document.body.scrollLeft,
        top: document.body.scrollTop
    }
}

//图片放大镜---------------------------------------------------