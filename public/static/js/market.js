$(document).ready(function() {
	$("#code_hot dl:first").addClass("dropList-hover");
	$("#code_hot dl").mouseover(function() {
		$("#code_hot dl.dropList-hover").removeClass("dropList-hover");
		$(this).addClass("dropList-hover")
	});
	$(".shop-evaluation-label label").on("click", function() {
		var a = $(this).attr("data").split("|");
		$(".shop-evaluation-score p").each(function(b) {
			$(this).html(a[b])
		})
	});
	0 < $(".shop_nav a").length && 0 < $(".shop_nav a[href='" + location.href + "']").length && $(".shop_nav a[href='" +
		location.href + "']").addClass("cur");
	$("#shop_search").click(function() {
		var a = "";
		$(this).closest("div").find(":text").each(function() {
			"" != $(this).val() && (a += $(this).attr("name") + "/" + $(this).val() + "/")
		});
		if ("" == a) return layer.alert(
			"\u4eb2\uff0c\u81f3\u5c11\u9700\u8981\u8f93\u51651\u9879\u641c\u7d22\u5185\u5bb9\uff01", {
				icon: 0
			},
			function(a) {
				$("#shop_key").focus();
				layer.close(a)
			}), !1;
		location.href = $("input:radio[name='good_type']:checked").val() + a
	});
	$("#sobid").click(function() {
		var a = $(this).closest("ul").find(":text");
		if ("" == a.val()) return layer.alert(
			"\u4eb2\uff0c\u8bf7\u8f93\u5165\u8981\u641c\u7d22\u7684\u6295\u6807\u53f7\uff01", {
				icon: 0
			},
			function(b) {
				a.focus();
				layer.close(b)
			}), !1;
		selist(0, Number(a.val()));
		$("#sobid_reset").show();
		$("#sobid_reset,.filter-comment label").click(function() {
			$(".bid_tit :text").val("");
			$("#sobid_reset").hide();
			"sobid_reset" == $(this).attr("id") && selist()
		})
	});
	$("#brand_button[data-number]").click(function() {
		$.get("/html/claimed/" + $(this).data("number"), function(a) {
			layer.open({
				type: 1,
				title: !1,
				shade: [.3, "#000"],
				area: ["420px"],
				content: a
			})
		})
	});
	$(".cartadd").on("click", function(a) {
		var b = $(this),
			c = b.attr("id"),
			d = b.attr("mode"),
			f = b.attr("title"),
			e = b.attr("data"),
			g = b.attr("scene");
		if (0 == d) return layer.confirm(("code" != g ?
				'<b style="color:blue">\u8d2d\u4e70\u524d\u8bf7\u786e\u4fdd\u60a8\u5df2\u4e0e\u5356\u5bb6\u6c9f\u901a\u786e\u8ba4\u4e86\u4ea4\u6613\u5546\u54c1\u7ec6\u8282\uff01</b><p style="color:#666;">\u5426\u5219\u4e0d\u5efa\u8bae\u60a8\u76f4\u63a5\u8d2d\u4e70\uff0c\u907f\u514d\u5f53\u524d\u5546\u54c1\u5356\u5bb6\u5df2\u505c\u552e\u6216\u552e\u51fa\uff01</p>' :
				'<b style="color:blue">\u8be5\u5546\u54c1\u9700\u5546\u5bb6\u3010\u624b\u52a8\u53d1\u8d27\u3011\uff0c\u8bf7\u786e\u8ba4\u5df2\u4e0e\u5546\u5bb6\u53d6\u5f97\u8054\u7cfb\uff01</b><p style="color:#666;">\u82e5\u5546\u5bb6\u4e0d\u5728\u7ebf\uff0c\u4e0d\u5efa\u8bae\u60a8\u8d2d\u4e70\uff0c\u4ee5\u514d\u957f\u65f6\u95f4\u7b49\u5f85\u5176\u53d1\u8d27\uff01</p>'
			) +
			'<div class="layui-layer-btn layui-layer-btn-" style="position: absolute;left:0;bottom: 0;padding:0;width:100%;"><a class="layui-layer-btn0 cartadd" id="' +
			c + '" title="' + f + '" data="' + e + '" info="' + b.attr("info") + '">' + f +
			'</a><a class="layui-layer-btn1" target="_blank">\u8054\u7cfb\u5546\u5bb6</a></div>', {
				icon: 0,
				area: ["450px", "185px"],
				btn: !1
			},
			function(a) {
				layer.close(a)
			},
			function(a) {
				layer.close(a);
				SellContact()
			}), !1;

	});
	$(".report").click(function() {
		layer.load();
		$.get("/html/report", {
			good: $(this).attr("good"),
			number: $(this).attr("number")
		}, function(a) {
			layer_ly(a, !1, !0, "800px")
		})
	});
	$(".clist dt,.wlist .pic").mouseenter(function() {
		var a = $(this);
		enter = setTimeout(function() {
				a.find(".ly").show()
			},
			300)
	}).mouseleave(function() {
		clearTimeout(enter);
		$(this).find(".ly").hide()
	});
	$(".list .slist").hover(function() {
		$(this).addClass("slcur")
	}, function() {
		$(this).removeClass("slcur")
	});
	$(".tlist ul,.tasklist ul").hover(function() {
		$(this).addClass("curr")
	}, function() {
		$(this).removeClass("curr")
	});

	$(".wlqq").click(function() {
		$(this).next().find("a:first").click()
	});
	$(".Jqnav a").on("click", function() {
		$(this).addClass("curr").siblings().removeClass("curr");
		$($(this).parent().parent().next().children().get($(this).index())).removeClass("hide").siblings().addClass(
			"hide")
	});
	$(".demo").click(function() {
		if (1 == $(this).attr("tips")) return !0;
		$.post("/html/demo/", {
			U: this.href,
			M: $(this).attr("mode")
		}, function(a) {
			layer_ly(a, !1, !1, "495px")
		});
		return !1
	});
	$(".sort_page a:not([data-ajax])").click(function() {
		var a = $("#page .ohave"),
			b = $(this).attr("id");
		a = ("l" == b ? a.prev() : a.next()).find("a");
		if (0 >= a.length) return layer.alert("\u4eb2\uff0c\u5df2\u7ecf\u662f" + ("l" == b ? "\u7b2c\u4e00" :
			"\u6700\u540e\u4e00") + "\u9875\u5566\uff01", {
			icon: 5
		}), !1;
		location.href = a.attr("href")
	});
	$(".sort_page a[data-ajax]").click(function() {
		var a = parseInt($(this).siblings("#curr-page").html()),
			b = parseInt($(this).siblings("#total-page").html());
		a = "l" == $(this).attr("id") ? a - 1 : a + 1;
		if (a > b || 0 >= a) return layer.alert("\u4eb2\uff0c\u5df2\u7ecf\u662f" + (a < b ? "\u7b2c\u4e00" :
			"\u6700\u540e\u4e00") + "\u9875\u5566\uff01", {
			icon: 5
		});
		fixed_tool(a)
	});
	$(".sort_select dl").hover(function() {
		var a = $(this).attr("data");
		a = $(this).find("a[data='" + a + "']");
		(0 >= a.length ? $(this).find("a:first") : a).addClass("curr");
		$(this).addClass("curr").siblings("dl").removeClass("curr");
		$(this).find("dd").show()
	}, function() {
		$(this).removeClass("curr").find("dd").hide()
	});
	$(".lrtop cite  a").click(function() {
		$(this).addClass("curr").siblings().removeClass("curr").closest("dl").next(".post").find("div:eq(" + $(this).index() +
			")").removeClass("hide").siblings().addClass("hide")
	});
	$(".shopso").click(function() {
		var a = $(this).closest("div");
		if (0 >= a.find('input[value!=""]').length) return layer.alert(
			"\u4eb2\uff0c\u641c\u7d22\u5185\u5bb9\u4e0d\u80fd\u4e3a\u7a7a\uff01", {
				icon: 5
			},
			function(b) {
				a.find("input:eq(0)").focus();
				layer.close(b)
			}), !1;
		var b = window.location.pathname.split("/"),
			c = $.inArray("page", b);
		0 < c && b.splice(c, 2);
		$.each($('.sort_input :text[data!=""],.jsearch :text[data!=""]'), function(a, c) {
			a = $(c).attr("data");
			c = $.trim($(c).val());
			var d = $.inArray(a, b);
			"" == c ? 0 < d && b.splice(d, 2) : 0 < d ? b[d + 1] = c : b.push(a, c)
		});
		b = $.grep(b, function(a) {
			return 0 < a.length
		});
		$url = b.join("/");
		location.href = "/" + $url
	})
});
$(".j-number a").on("click", function() {
	var a = $(this).parent().find(".j-number-input"),
		b = parseInt(a.attr("maxval")),
		c = $(this).attr("class");
	c = parseInt(a.val()) + ("up" == c ? 1 : -1);
	0 > c || 0 == b ? a.val(0) : 0 >= c && 0 != b ? a.val(1) : 0 > b ? a.val(9999 > c ? c : 9999) : c > b ? a.val(b) : a
		.val(c);
	Jtotal()
});
$(".j-number-input").on("blur", function() {
	var a = parseInt($(this).attr("maxval")),
		b = parseInt($(this).val());
	b = 0 < b ? b : 1;
	0 > b || 0 == a ? $(this).val(0) : 0 >= b && 0 != a ? $(this).val(1) : 0 > a ? $(this).val(9999 > b ? b : 9999) : b >
		a ? $(this).val(a) : $(this).val(b);
	Jtotal()
});


$(".upbid").on("click", function() {
	var a = $(this).attr("id");
	$.post("/html/upbid", {
		number: a
	}, function(b) {
		if (-2 == b) return layer_lp("\u67e5\u770b\u7edf\u8ba1", a), !1;
		layer_ly(b, " ", !1)
	})
});
$(".outbid").on("click", function() {
	var a = $(this);
	layer.confirm(
		"<b>\u60a8\u786e\u5b9a\u8981<font color=red>\u6dd8\u6c70\u6b64\u6807</font>\u5417\uff1f</b><br><font color=#999999>\u6dd8\u6c70\u540e\u8be5\u6295\u6807\u65e0\u6cd5\u518d\u4fee\u6539\u548c\u9009\u4e2d\u6807\uff01</font>", {
			icon: 3
		},
		function(b) {
			Aform("outbid", "number=" + a.attr("number"), function(b) {
				1 == b.state ? a.closest("li").empty().closest("dt").find(".bicon").html(
						'<i class="out"><font style="font-size:16px;">&#xe64b;</font>\u6dd8\u6c70</i>') :
					Rs(b)
			});
			layer.close(b)
		})
});
$(".sobid").on("click", function() {
	scTop(".layui-form");
	$("input[name=sobid]").val($(this).html());
	$("#sobid").click()
});
$(".computing_act i:not(.no)").on("click", function() {
	var a = $("#p_number").val(),
		b = 1;
	"sub" == $(this).attr("data") && (b = -1);
	a = parseInt(a) + b;
	$("#p_number").val(a);
	1 >= a ? $(".computing_act i[data='sub']").addClass("no") : $(".computing_act i[data='sub']").removeClass("no")
});
$("#p_down,#p_up").on("click", function() {
	var a = $(this).attr("class"),
		b = $(this).attr("id"),
		c = parseInt($(".curPage").html());
	"no" != a && ($(".s_ajax_page input[type=text]").val(""), selist("p_up" == b ? c - 1 : c + 1))
});
$(".gopage").on("click", function() {
	var a = $(this).prev().val();
	a > parseInt($(".totalPage").html()) ? layer.alert("\u4e0d\u80fd\u8d85\u8fc7\u6700\u5927\u9875\u6570\uff1a" + $(
		".totalPage").html(), {
		icon: 0
	}) : 1 > a ? layer.alert("\u4e0d\u80fd\u4f4e\u4e8e\u6700\u5c0f\u9875\u6570\uff1a1", {
		icon: 0
	}) : selist(a)
});

function set_cur(a) {
	$(".c_r_menu em").hasClass("cur") && $(".c_r_menu em").removeClass("cur");
	$(".c_r_menu em" + a).addClass("cur")
}

function getPageBar(a, b) {
	$("#p_up,#p_down").addClass("no");
	1 < a && $("#p_up").removeClass("no");
	a < b && $("#p_down").removeClass("no")
}
$(".ICheckbox:not([data-input]) label,.Big-ICheckbox:not([data-input]) label").on("click", function() {
	$(this).toggleClass("IChecked")
});
$(".IRadio label,.Big-IRadio label").on("click", function() {
	$(this).addClass("IChecked").siblings("label").removeClass("IChecked")
});
$(".filter-comment label").on("click", function() {
	selist()
});
gelist = function(a, b, c) {
	a = a || 0;
	1 < a && scTop("#c_bb", 45);
	$.ajax({
		type: "POST",
		url: "/apage/",
		async: !0,
		data: "list=geva&pro=" + readmeta("Item-Number") + "&good=" + readmeta("Pg-Type") + "&page=" + a,
		dataType: "json",
		success: function(a) {
			$.each(a, function(a, b) {
				$("." + a).empty();
				$("." + a).html(b)
			});
			getPageBar(a.curPage, a.totalPage);
			$(".c_r_menu").menu_layer();
			layer_photos()
		},
		error: function() {
			layer.msg("\u52a0\u8f7d\u5931\u8d25\uff0c\u8bf7\u91cd\u8bd5\uff01");
			layer.close(loading);
			return !1
		}
	})
};
selist = function(a, b) {
	if ($(".c_r_menu").length > 0) return gelist(a, b);
	a = a || 0;
	var c = readmeta("Pg-Type");
	b = b || $(".filter-comment .IChecked").attr("data");
	1 < a && scTop("#shop_pg_scTop", 140);
};
$.fn.extend({
	layer_top: function() {
		var a = $(this).offset().top + 40,
			b = $(this);
		$(window).scroll(function() {
			$(window).scrollTop() > a ? b.addClass("fixed") : b.removeClass("fixed")
		})
	}
});
$.fn.extend({
	menu_layer: function() {
		var a = $("#c_aa").offset().top,
			b = $("#c_bb").offset().top,
			c = $("#c_cc").offset().top;
		$(window).scroll(function() {
			var d = $(this).scrollTop() + 46;
			d >= c ? set_cur(".c_cc") : d >= b ? set_cur(".c_bb") : d >= a && set_cur(".c_aa")
		});
		$(".c_r_menu em:not([a])").click(function() {
			var a = $(this).attr("class").replace(/cur/, "");
			$("html,body").scrollTop($("#" + a).offset().top - 43);
			$(this).addClass("cur").siblings("em").removeClass("cur")
		})
	}
});
$.fn.extend({
	insertAtCaret: function(a) {
		var b = $(this)[0];
		if (document.selection) this.focus(), sel = document.selection.createRange(), sel.text = a, this.focus();
		else if (b.selectionStart || "0" == b.selectionStart) {
			var c = b.selectionStart,
				d = b.selectionEnd,
				f = b.scrollTop;
			b.value = b.value.substring(0, c) + a + b.value.substring(d, b.value.length);
			this.focus();
			b.selectionStart = c + a.length;
			b.selectionEnd = c + a.length;
			b.scrollTop = f
		} else this.value += a, this.focus()
	}
});
$(".exchange-btn a:not(.not)").on("click", function() {
	layer.load();
	var a = $(this),
		b = $(this).attr("number");
	Aform("u", "", function(c) {
		if (-2 == c.state) return layer_login("\u5151\u6362", "." + a.attr("class")), layer.closeAll("loading"), !1;
		$.post("/deal/exchange", {
			number: b,
			piece: $("input[name=piece]").val()
		}, function(a) {
			layer_ly(a, "\u5151\u6362", !1, "700px")
		})
	})
});
$(".demo_box .layui-btn").on("click", function() {
	layer.closeAll();
	return $(this).hasClass("demo_web") ? !0 : SellContact()
});
