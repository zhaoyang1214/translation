# 这是一个将数组字段及值映射为相应文本的类

## 特性
* 支持多维数组
* 支持新旧数据变更翻译
* 配置简单且多样
* 支持json数据
* 支持扩展

## 安装
#### 使用composer安装
```
composer require snow/translation
```

## 一、使用
1、简单使用
```php
use Snow\Translation\Translation;

$configs = [
    'name' => '公告名称'
];
$data = [
    'name' => '我是一个方案'
];
$tpl = '【:attribute】值为“:value”';
$tran = new Translation($configs, $tpl);
$tran->translate($data);
echo $tran; // 【方案名称】值为“我是一个方案”
echo $tran->getTranslateString(); // 【方案名称】值为“我是一个方案”
```

2、支持值映射
```php
use Snow\Translation\Translation;

$configs = [
    'type' => [
        'text' => '公告类型',
        'value' => [1 => '资讯类', 2 => '公告类', 3 => '广告类']
    ],
];
$data = [
    'type' => 3
];
$tpl = '【:attribute】值为“:value”';
$tran = new Translation($configs, $tpl);
$tran->translate($data);
echo $tran->getTranslateString(); // 【公告类型】值为“广告类”
```

3、支持关联字段映射,解决字段复用问题
```php
use Snow\Translation\Translation;

$configs = [
    'type' => [
        'text' => '公告类型',
        'value' => [1 => '资讯类', 2 => '公告类', 3 => '广告类']
    ],
    'extra.jump_type' => [
        'text' => '跳转类型',
        'value' => ['webview' => '网页类', 'download' => '下载类', 'deeplink' => '拉起应用类', 'noclick' => '不可点击']
    ],
    'extra.url' => [
        'relevance' => [
            'type' => [
                1 => '链接',
                2 => '链接',
                3 => [
                    'relevance' => [
                        'extra.jump_type' => [
                            'webview' => '广告链接',
                            'download' => '广告链接',
                            'deeplink' => '备用链接',
                        ]
                    ]
                ],
            ],
        ],
    ],
];
$data = [
    'type' => 3,
//    'extra' => '{"title":"标题111", "jump_type":"deeplink", "img":"图片地址", "deeplink":"deeplink://", "url":"http://www.baidu.com"}', // 支持json
    'extra' => json_decode('{"title":"标题111", "jump_type":"deeplink", "img":"图片地址", "deeplink":"deeplink://", "url":"http://www.baidu.com"}', true),
];
$tpl = '【:attribute】值为“:value”';
$tran = new Translation($configs, $tpl);
$tran->translate($data);
echo $tran->getTranslateString(); // 【公告类型】值为“广告类”;【跳转类型】值为“拉起应用类”;【备用链接】值为“http://www.baidu.com”
```

4、值映射支持callback
```php
use Snow\Translation\Translation;

$configs = [
    'plan_id' => [
        'text' => '方案名称',
        'value' => function ($value, $attribute, $translation) {
            if (empty($value)) {
                return null;
            }
            // 查询
            $res = [
                [
                    'id' => 1,
                    'plan_name' => '公告1',
                ],
                [
                    'id' => 2,
                    'plan_name' => '公告2',
                ]
            ];
            $arr = array_column($res, 'plan_name', 'id');
            return $arr[$value];
        }
    ],
];
$data = [
    'plan_id' => 1
];
$tpl = '【:attribute】值为“:value”';
$tran = new Translation($configs, $tpl);
$tran->translate($data);
echo $tran->getTranslateString(); // 【方案名称】值为“公告1”
```

5、支持新旧数据变更翻译
```php
use Snow\Translation\Translation;

$configs = [
    'name' => '公告名称',
    'type' => [
        'text' => '公告类型',
        'value' => [1 => '资讯类', 2 => '公告类', 3 => '广告类']
    ],
    'extra.title' => '标题',
    'extra.img' => '图片',
    'extra.deeplink' => 'deeplink',
    'extra.jump_type' => [
        'text' => '跳转类型',
        'value' => ['webview' => '网页类', 'download' => '下载类', 'deeplink' => '拉起应用类', 'noclick' => '不可点击']
    ],
    'extra.url' => [
        'relevance' => [
            'type' => [
                1 => '链接',
                2 => '链接',
                3 => [
                    'relevance' => [
                        'extra.jump_type' => [
                            'webview' => '广告链接',
                            'download' => '广告链接',
                            'deeplink' => '备用链接',
                        ]
                    ]
                ],
            ],
        ],
    ],
];
$data = [
    'name' => '方案内容',
    'type' => 3,
    'extra' => '{"title":"标题111", "jump_type":"deeplink", "img":"图片地址", "deeplink":"deeplink://", "url":"http://www.baidu.com"}',
];
$oldData = [
    'name' => '方案内容2',
    'type' => 2,
    'extra' => '{"title":"标题222", "img":"图片222://", "url":"http://www.baidu2222.com"}',
];

$tpl = '【:attribute】值由“:oldValue”改为“:value”';
$tran = new Translation($configs, $tpl);
$tran->translate($data, $oldData);
echo $tran->getTranslateString(); // 【公告名称】值由“方案内容2”改为“方案内容”;【公告类型】值由“公告类”改为“广告类”;【标题】值由“标题222”改为“标题111”;【图片】值由“图片222://”改为“图片地址”;【deeplink】值由“”改为“deeplink://”;【跳转类型】值由“”改为“拉起应用类”;【备用链接】值由“”改为“http://www.baidu.com”
```

