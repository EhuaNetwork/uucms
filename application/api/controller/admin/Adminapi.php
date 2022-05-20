<?php


namespace app\api\controller\admin;


use app\admin\controller\Common;
use think\Controller;

class Adminapi extends Common
{
    public function getmenu()
    {
        $admPath = $this->admPath;
        $data = [
            "homeInfo" => [
                "title" => "首页",
                "href" => url($admPath . '/index/controll')
            ],
            "logoInfo" => [
                "title" => "UUCMS",
                "image" => "/favicon.png",
                "href" => ""
            ],
            "menuInfo" => [
                [
                    "title" => "常规管理",
                    "icon" => "fa fa-address-book",
                    "href" => "",
                    "target" => "_self",
                    "child" => [
                        [
                            "title" => "站点设置",
                            "href" => "",
                            "icon" => "fa fa-home",
                            "target" => "_self",
                            "child" => [
                                [
                                    "title" => "系统设置",
                                    "href" => url($admPath . '/index/system'),
                                    "icon" => "fa fa-tachometer",
                                    "target" => "_self"
                                ],
                                [
                                    "title" => "变量设置",
                                    "href" => url($admPath . '/index/load'),
                                    "icon" => "fa fa-tachometer",
                                    "target" => "_self"
                                ],
                                [
                                    "title" => "SEO",
                                    "href" => url($admPath . '/index/seo'),
                                    "icon" => "fa fa-tachometer",
                                    "target" => "_self"
                                ]
                            ]
                        ],
                        [
                            "title" => "栏目管理",
                            "href" => "",
                            "icon" => "fa fa-home",
                            "target" => "_self",
                            "child" => [
                                [
                                    "title" => "栏目列表",
                                    "href" => url($admPath . '/mod/index'),
                                    "icon" => "fa fa-tachometer",
                                    "target" => "_self"
                                ],
                                [
                                    "title" => "添加栏目",
                                    "href" => url($admPath . '/mod/create'),
                                    "icon" => "fa fa-tachometer",
                                    "target" => "_self"
                                ]
                            ]
                        ],
                        [
                            "title" => "内容管理",
                            "href" => "",
                            "icon" => "fa fa-home",
                            "target" => "_self",
                            "child" => [
                                [
                                    "title" => "内容列表",
                                    "href" => url($admPath . '/article/lists'),
                                    "icon" => "fa fa-tachometer",
                                    "target" => "_self"
                                ],
                                [
                                    "title" => "添加内容",
                                    "href" => url($admPath . '/article/create'),
                                    "icon" => "fa fa-tachometer",
                                    "target" => "_self"
                                ]
                            ]
                        ],
                        [
                            "title" => "首图管理",
                            "href" => "",
                            "icon" => "fa fa-home",
                            "target" => "_self",
                            "child" => [
                                [
                                    "title" => "图片列表",
                                    "href" => url($admPath . '/banner/lists'),
                                    "icon" => "fa fa-tachometer",
                                    "target" => "_self"
                                ],
                                [
                                    "title" => "添加图片",
                                    "href" => url($admPath . '/banner/create'),
                                    "icon" => "fa fa-tachometer",
                                    "target" => "_self"
                                ]
                            ]
                        ],
                        [
                            "title" => "客户管理",
                            "href" => "",
                            "icon" => "fa fa-home",
                            "target" => "_self",
                            "child" => [
                                [
                                    "title" => "询盘列表",
                                    "href" => url($admPath . '/echos/lists'),
                                    "icon" => "fa fa-tachometer",
                                    "target" => "_self"
                                ]
                            ]
                        ],
                        [
                            "title" => "友链管理",
                            "href" => "",
                            "icon" => "fa fa-home",
                            "target" => "_self",
                            "child" => [
                                [
                                    "title" => "友链列表",
                                    "href" => url($admPath . '/link/lists'),
                                    "icon" => "fa fa-tachometer",
                                    "target" => "_self"
                                ], [
                                    "title" => "添加友链",
                                    "href" => url($admPath . '/link/create'),
                                    "icon" => "fa fa-tachometer",
                                    "target" => "_self"
                                ]
                            ]
                        ]
                    ]
                ],
                [
                    "title" => "插件管理",
                    "icon" => "fa fa-lemon-o",
                    "href" => "#",
                    "target" => "_self",
                    "child" => [

                        [
                            "title" => "官方插件",
                            "href" => '',
                            "icon" => "fa fa-tachometer",
                            "target" => "_self",
                            "child" => [
                                [
                                    "title" => "插件列表",
                                    "href" => url($admPath . '/authority/index'),
                                    "icon" => "fa fa-tachometer",
                                    "target" => "_self"
                                ], [
                                    "title" => "上传插件",
                                    "href" => url($admPath . '/authority/create'),
                                    "icon" => "fa fa-tachometer",
                                    "target" => "_self"
                                ]
                            ]

                        ],
                        [
                            "title" => "我的插件",
                            "href" => '',
                            "icon" => "fa fa-tachometer",
                            "target" => "_self",
                            "child" => [
                                [
                                    "title" => "插件列表",
                                    "href" => url($admPath . '/mine/index'),
                                    "icon" => "fa fa-tachometer",
                                    "target" => "_self"
                                ], [
                                    "title" => "上传插件",
                                    "href" => url($admPath . '/mine/create'),
                                    "icon" => "fa fa-tachometer",
                                    "target" => "_self"
                                ]
                            ]
                        ]

                    ]
                ],
                [
                    "title" => "其它管理",
                    "icon" => "fa fa-slideshare",
                    "href" => "#",
                    "target" => "_self",
                    "child" => [
                        [
                            "title" => "待更新",
                            "href" => url($admPath . '/index/controll3'),
                            "icon" => "fa fa-tachometer",
                            "target" => "_self"
                        ]
                    ]
                ]
            ]
        ];


        return json($data);
    }


}