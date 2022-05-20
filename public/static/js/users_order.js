// 全局变量参数
var PayPolling;
var selectTypeID = UnifiedID = PayID = CustomMoney = 0;
var selectProduct = selectDomain = selectPayMethod = UnifiedNumber = PayMark = selectOrderCode = selectWeappCode = '';

// 传入的参数
// console.log(JsonData);
var UnifiedPayBuyUrl = JsonData.UnifiedPayBuyUrl;
var OrderPayPollingUrl = JsonData.OrderPayPollingUrl;

// 立即购买
function Authorization(type_id, product) {
    // 未登录则跳转登录
    if (0 >= ey_getCookie('users_id')) {
        layerLogin();
        return false;
    }

    // 授权域名
    var domain = '';
    if (1 == type_id || 2 == type_id) {
        // 商业授权(基础版、专业版)
        layer.prompt({
            title: '填写授权域名',
            id: 'EnterDomainID',
            btn: ['暂无域名', '确定'],
            success: function(layero, index) {
                $("#EnterDomainID").find('input').attr('placeholder', '如：eyoucms.com');
                $("#EnterDomainID").find('input').bind('keydown', function(event){
                    if (event.keyCode == 13) {
                        var InputDomain = $(this).val();
                        domain = InputDomain ? InputDomain : '';
                        UnifiedPaySelect(type_id, product, domain);
                    }
                });
            }, yes: function(index, layero) {
                UnifiedPaySelect(type_id, product, domain);
            }, btn2: function(index, layero) {
                var InputDomain = $('#EnterDomainID').find('input').val();
                if (!InputDomain || InputDomain == '') {
                    $("#EnterDomainID").find('input').focus();
                    return false;
                }
                domain = InputDomain;
                UnifiedPaySelect(type_id, product, domain);
            }
        });
    } else if (2 < type_id) {
        // 其他授权
        UnifiedPaySelect(type_id, product, domain);
    }
}

// 支付方式选择
function UnifiedPaySelect(type_id, product, domain, order_code, weapp_code) {
    // 关闭其他弹窗
    layer.closeAll();

    // 未登录则跳转登录
    if (0 >= ey_getCookie('users_id')) {
        layerLogin();
        return false;
    }
    
    // 记录选择的版本信息
    selectTypeID = type_id ? type_id : 0;
    selectProduct = product ? product : '';
    selectDomain = domain ? domain : '';
    selectOrderCode = order_code ? order_code : '';
    selectWeappCode = weapp_code ? weapp_code : '';

    // 如果是代理并且又授权次数则执行
    if("undefined" != typeof UsersProxyData){ 
        if (UsersProxyData.level == 4 || UsersProxyData.level == 5) {
            if (type_id == 1 && 0 < UsersProxyData.proxy_domain_num) {
                // 普通版授权，默认微信支付，仅防止报错
                UnifiedPayBuy('wechat');
                return false;
            } else if (type_id == 2 && 0 < UsersProxyData.proxy_domain_num_zy) {
                // 专业版授权，默认微信支付，仅防止报错
                UnifiedPayBuy('wechat');
                return false;
            }
        }
    }

    // 弹出支付选择
    var content = '<style type="text/css">body .WeChatScanCode .layui-layer-content{padding:0px;}</style>';
    content += '<div style="margin-top: 20px;">';
    content += '<a href="javascript:void(0);" onclick="layer.closeAll();parent.UnifiedPayBuy(\'alipay\');" style="float: left;">';
    content += ' <img src="/public/static/common/images/alipay.png" width="150" height="50" alt="支付宝支付" />';
    content += '</a>';
    content += '<a href="javascript:void(0);" onclick="layer.closeAll();parent.UnifiedPayBuy(\'wechat\');" style="float: right;">';
    content += ' <img src="/public/static/common/images/weipay.png" width="150" height="50" alt="微信支付" />';
    content += '</a>';
    content += '</div>';
    layer.open({
        type: 1,
        title: '选择支付方式',
        shadeClose: false,
        maxmin: false,
        skin: 'WeChatScanCode',
        area: ['320px', '150px'],
        content: content
    });
}

// 统一下单支付购买接口
function UnifiedPayBuy(payMethod,mianfei) {
    if (!mianfei) {
        mianfei = false;
    }
    // 支付方式
    selectPayMethod = payMethod;

    // 加载提示
    LayerLoading('正在处理');

    // AJAX调用支付
    $.ajax({
        type: 'post',
        url : UnifiedPayBuyUrl,
        data: {
            domain: selectDomain,
            type_id: selectTypeID,
            product: selectProduct,
            custom_money:CustomMoney,
            pay_method: selectPayMethod,
            join_order_code:selectOrderCode,
            weapp_code:selectWeappCode,
        },
        dataType : 'json',
        success : function(res) {
            layer.closeAll();
            if (1 == res.code) {
                // 若是代理使用次数完成授权则执行
                if ('proxy' == res.data.pay_mark) {
                    layer.msg(res.msg, {time: 1500} ,function() {
                        window.location.reload();
                    });
                } else {
                    // 支付方式ID
                    PayID = res.data.pay_id;
                    // 支付方式标识
                    PayMark = res.data.pay_mark;
                    // 订单ID
                    UnifiedID = res.data.unified_id;
                    // 订单号
                    UnifiedNumber = res.data.unified_number;
                    // 对应的支付方式
                    if ('alipay' == payMethod) {
                        // 支付宝支付
                        // LayerLoading('正在支付');
                        PayPolling = window.setInterval(function(){ OrderPayPolling(res.data); }, 2000);
                        if (!mianfei){
                            window.open(res.data.alipay_url, '_blank ');
                            layer.confirm('请在新打开的页面进行支付！', {
                                title: false,
                                btn: ['支付成功', '支付失败'], //按钮
                                cancel: function(index, layero){
                                    window.location.reload();
                                    return false;
                                }
                            }, function () {
                                window.location.reload();
                            }, function (index) {
                                layer.close(index);
                            });
                        }
                    } else if ('wechat' == payMethod) {
                        // 微信支付
                        AlertPayImg(res.data);
                    } else {
                        layer.msg('请选择支付方式', {time: 2000});
                    }
                }
            } else {
                layer.msg(res.msg, {time: 2000});
            }
        }
    });
}

// 微信支付弹窗
function AlertPayImg(data) {
    var html = "<img src='"+data.url_qrcode+"' style='width: 250px; height: 250px;'><br/><span style='color: red; display: inline-block; width: 100%; text-align: center;'>正在支付中...请勿刷新</span>";
    layer.alert(html, {
        title: false,
        btn: [],
        success: function() {
            PayPolling = window.setInterval(OrderPayPolling, 2000);
        },
        cancel: function() {
            window.clearInterval(PayPolling);
        }
    });
}

// 订单轮询
function OrderPayPolling() {
    // 检测信息是否完整
    if (!selectTypeID || !UnifiedID || !UnifiedNumber) {
        layer.msg('订单异常，刷新重试', {time: 1500}, function() {
            window.location.reload();
        });
    }

    // 执行轮询
    $.ajax({
        url : OrderPayPollingUrl,
        data: {
            pay_id: PayID,
            pay_mark: PayMark,
            type_id: selectTypeID,
            unified_id: UnifiedID,
            unified_number: UnifiedNumber
        },
        type:'post',
        dataType:'json',
        success:function(res) {
            if (1 == res.code) {
                if (res.data) {
                    window.clearInterval(PayPolling);
                    layer.msg(res.msg, {time: 1500}, function() {
                        window.location.href = res.url;
                    });
                }
            } else if (0 == res.code) {
                layer.alert(res.msg, {icon:0, title: false, closeBtn: 0});
            }
        }
    });
}

// 加载层
function LayerLoading(msg) {
    var loading = layer.msg(
    msg+'...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请勿刷新页面', 
    {
        icon: 1,
        time: 3600000, //1小时后后自动关闭
        shade: [0.2] //0.1透明度的白色背景
    });
    //loading层
    var index = layer.load(3, {
        shade: [0.1,'#fff'] //0.1透明度的白色背景
    });

    return loading;
}