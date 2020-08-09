<?php
namespace Snow\Translation\Tests;

use PHPUnit\Framework\TestCase;
use Snow\Translation\Translation;

class TranslationTest extends TestCase
{
    protected array $configs;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->configs = [
            'plan_id' => [
                'text' => '方案名称',
                'value' => function ($value, $attribute, $translation) {
                    if (!isset($value)) {
                        return null;
                    }
                    // 查询
                    $res = [
                        [
                            'id' => 1,
                            'plan_name' => '公告',
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
            'name' => '公告名称',
            'priority' => '优先级',
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
            'extra1' => '',
        ];
    }

    public function testTranslateData()
    {
        $configs = $this->configs;
        $data = [
            'plan_id' => 1,
            'name' => '方案内容',
            'priority' => 1,
            'type' => 3,
            'extra' => json_decode('{"title":"标题111", "jump_type":"deeplink", "img":"图片地址", "deeplink":"deeplink://", "url":"http://www.baidu.com"}', true),
        ];

        $tpl = '【:attribute】值为“:value”';
        $search = [':attribute', ':value', ':oldValue'];
        $tran = new Translation($configs, $tpl);
        $tran->translate($data);
        $res = [
            'plan_id' => str_replace($search, ['方案名称', '公告', ''], $tpl),
            'name' => str_replace($search, ['公告名称', '方案内容', ''], $tpl),
            'priority' => str_replace($search, ['优先级', '1', ''], $tpl),
            'type' => str_replace($search, ['公告类型', '广告类', ''], $tpl),
            'extra.title' => str_replace($search, ['标题', '标题111', ''], $tpl),
            'extra.img' => str_replace($search, ['图片', '图片地址', ''], $tpl),
            'extra.deeplink' => str_replace($search, ['deeplink', 'deeplink://', ''], $tpl),
            'extra.jump_type' => str_replace($search, ['跳转类型', '拉起应用类', ''], $tpl),
            'extra.url' => str_replace($search, ['备用链接', 'http://www.baidu.com', ''], $tpl),
        ];
        foreach ($res as $field => $t) {
            $this->assertEquals($tran->getTranslates($field), $t);
        }
        $this->assertEquals($tran->getTranslateString(), implode(';', $res));
        $this->assertEquals((string)$tran, implode(';', $res));
    }

    public function testTranslateJsonData()
    {
        $configs = $this->configs;
        $data = [
            'plan_id' => 1,
            'name' => '方案内容',
            'priority' => 1,
            'type' => 3,
            'extra' => '{"title":"标题111", "jump_type":"deeplink", "img":"图片地址", "deeplink":"deeplink://", "url":"http://www.baidu.com"}',
        ];

        $tpl = '【:attribute】值为“:value”';
        $search = [':attribute', ':value', ':oldValue'];
        $tran = new Translation($configs, $tpl);
        $tran->translate($data);
        $res = [
            'plan_id' => str_replace($search, ['方案名称', '公告', ''], $tpl),
            'name' => str_replace($search, ['公告名称', '方案内容', ''], $tpl),
            'priority' => str_replace($search, ['优先级', '1', ''], $tpl),
            'type' => str_replace($search, ['公告类型', '广告类', ''], $tpl),
            'extra.title' => str_replace($search, ['标题', '标题111', ''], $tpl),
            'extra.img' => str_replace($search, ['图片', '图片地址', ''], $tpl),
            'extra.deeplink' => str_replace($search, ['deeplink', 'deeplink://', ''], $tpl),
            'extra.jump_type' => str_replace($search, ['跳转类型', '拉起应用类', ''], $tpl),
            'extra.url' => str_replace($search, ['备用链接', 'http://www.baidu.com', ''], $tpl),
        ];
        foreach ($res as $field => $t) {
            $this->assertEquals($tran->getTranslates($field), $t);
        }
        $this->assertEquals($tran->getTranslateString(), implode(';', $res));
        $this->assertEquals((string)$tran, implode(';', $res));
    }

    public function testTranslateOldData()
    {
        $configs = $this->configs;
        $data = [
            'plan_id' => 1,
            'name' => '方案内容',
            'priority' => 1,
            'type' => 3,
            'extra' => '{"title":"标题111", "jump_type":"deeplink", "img":"图片地址", "deeplink":"deeplink://", "url":"http://www.baidu.com"}',
        ];
        $oldData = [
            'plan_id' => 2,
            'name' => '方案内容2',
            'priority' => 1,
            'type' => 2,
            'extra' => '{"title":"标题222", "img":"图片222://", "url":"http://www.baidu2222.com"}',
        ];
        $search = [':attribute', ':value', ':oldValue'];
        $tpl = '【:attribute】值由“:oldValue”改为“:value”';
        $tran = new Translation($configs, $tpl);
        $tran->translate($data, $oldData);
        $res = [
            'plan_id' => str_replace($search, ['方案名称', '公告', '公告2'], $tpl),
            'name' => str_replace($search, ['公告名称', '方案内容', '方案内容2'], $tpl),
            'type' => str_replace($search, ['公告类型', '广告类', '公告类'], $tpl),
            'extra.title' => str_replace($search, ['标题', '标题111', '标题222'], $tpl),
            'extra.img' => str_replace($search, ['图片', '图片地址', '图片222://'], $tpl),
            'extra.url' => str_replace($search, ['备用链接', 'http://www.baidu.com', ''], $tpl),
        ];
        foreach ($res as $field => $t) {
            $this->assertEquals($tran->getTranslates($field), $t);
        }
    }
}
