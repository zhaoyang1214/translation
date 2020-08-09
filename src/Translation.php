<?php
namespace Snow\Translation;

use Snow\Translation\FieldTranslator\FieldDefault;
use Snow\Translation\FieldTranslator\FieldRelevance;
use Snow\Translation\FieldTranslator\FieldText;
use Snow\Translation\ValueTranslator\ValueArray;
use Snow\Translation\ValueTranslator\ValueCallback;
use Snow\Translation\ValueTranslator\ValueDefault;

class Translation
{
    /** @var string 模板 */
    protected string $tpl = '';

    /** @var array 配置 */
    protected array $configs = [];

    /** @var array|string 数据 */
    protected $data;

    /** @var null|array|string 旧数据 */
    protected $oldData = null;

    /** @var array 字段翻译 */
    protected array $fieldTranslates = [];

    /** @var array 旧字段翻译 */
    protected array $oldFieldTranslates = [];

    /** @var array 值翻译 */
    protected array $dataTranslates = [];

    /** @var array 旧值翻译 */
    protected array $oldDataTranslates = [];

    /** @var array 翻译数据 */
    protected array $translates = [];

    public function __construct(array $configs, string $tpl = '')
    {
        $this->configs = $configs;
        $this->tpl = $tpl;
    }

    /**
     * 功   能：获取模板
     * 修改日期：2020/7/28
     *
     * @return string
     */
    public function getTpl()
    {
        return $this->tpl;
    }

    /**
     * 功   能：设置模板
     * 修改日期：2020/7/28
     *
     * @param string $tpl
     * @return $this
     */
    public function setTpl(string $tpl)
    {
        $this->tpl = $tpl;
        return $this;
    }

    /**
     * 功   能：获取配置
     * 修改日期：2020/7/28
     *
     * @return array
     */
    public function getConfigs()
    {
        return $this->configs;
    }

    /**
     * 功   能：设置配置
     * 修改日期：2020/7/28
     *
     * @param array $configs
     * @return $this
     */
    public function setConfigs(array $configs)
    {
        $this->configs = $configs;
        return $this;
    }

    /**
     * 功   能：获取数据
     * 修改日期：2020/7/28
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * 功   能：设置数据
     * 修改日期：2020/7/28
     *
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * 功   能：获取旧数据
     * 修改日期：2020/7/28
     *
     * @return array|null
     */
    public function getOldData()
    {
        return $this->oldData;
    }

    /**
     * 功   能：设置旧数据
     * 修改日期：2020/7/28
     *
     * @param array $oldData
     * @return $this
     */
    public function setOldData(array $oldData)
    {
        $this->oldData = $oldData;
        return $this;
    }

    /**
     * 功   能：获取字段翻译
     * 修改日期：2020/7/28
     *
     * @param string|null $field
     * @return array|mixed|null
     */
    public function getFieldTranslates(string $field = null)
    {
        return isset($field) ? ($this->fieldTranslates[$field] ?? null) : $this->fieldTranslates;
    }

    /**
     * 功   能：设置字段翻译
     * 修改日期：2020/7/28
     *
     * @param string $field
     * @param string|null $translate
     * @return $this
     */
    public function setFieldTranslates(string $field, $translate)
    {
        $this->fieldTranslates[$field] = $translate;
        return $this;
    }

    /**
     * 功   能：获取旧字段翻译
     * 修改日期：2020/7/28
     *
     * @param string $field
     * @return array|mixed|null
     */
    public function getOldFieldTranslates(string $field = null)
    {
        return isset($field) ? ($this->oldFieldTranslates[$field] ?? null) : $this->oldFieldTranslates;
    }

    /**
     * 功   能：设置旧字段翻译
     * 修改日期：2020/7/28
     *
     * @param string $field
     * @param string|null $translate
     * @return $this
     */
    public function setOldFieldTranslates(string $field, $translate)
    {
        $this->oldFieldTranslates[$field] = $translate;
        return $this;
    }

    /**
     * 功   能：获取数据翻译
     * 修改日期：2020/7/28
     *
     * @param string $field
     * @return array|mixed|null
     */
    public function getDataTranslates(string $field)
    {
        return isset($field) ? ($this->dataTranslates[$field] ?? null) : $this->dataTranslates;
    }

    /**
     * 功   能：设置数据翻译
     * 修改日期：2020/7/28
     *
     * @param string $field
     * @param string|null $translate
     * @return $this
     */
    public function setDataTranslates(string $field, $translate)
    {
        $this->dataTranslates[$field] = $translate;
        return $this;
    }

    /**
     * 功   能：获取旧数据翻译
     * 修改日期：2020/7/28
     *
     * @param string $field
     * @return array|mixed|null
     */
    public function getOldDataTranslates(string $field)
    {
        return isset($field) ? ($this->oldDataTranslates[$field] ?? null) : $this->oldDataTranslates;
    }

    /**
     * 功   能：设置旧数据翻译
     * 修改日期：2020/7/28
     *
     * @param string $field
     * @param string|null $translate
     * @return $this
     */
    public function setOldDataTranslates(string $field, $translate)
    {
        $this->oldDataTranslates[$field] = $translate;
        return $this;
    }

    /**
     * 功   能：追加翻译
     * 修改日期：2020/7/28
     *
     * @param array $translate
     * @return $this
     */
    protected function appendTranslate(array $translate)
    {
        $this->translates = array_merge($this->translates, $translate);
        return $this;
    }

    /**
     * 功   能：获取翻译
     * 修改日期：2020/7/28
     *
     * @param string|null $filed
     * @return array|mixed|string
     */
    public function getTranslates(string $filed = null)
    {
        return is_null($filed) ? $this->translates : ($this->translates[$filed] ?? '');
    }

    /**
     * 功   能：组装翻译结果
     * 修改日期：2020/7/28
     *
     * @return void
     */
    protected function assemble()
    {
        foreach ($this->dataTranslates as $field => $value) {
            $fieldTranslate = $this->getFieldTranslates($field);
            $oldFieldTranslate = $this->getOldFieldTranslates($field);
            $oldValue = $fieldTranslate == $oldFieldTranslate ? $this->getOldDataTranslates($field) : null;
            if ($value !== $oldValue) {
                $this->appendTranslate([
                    $field => str_replace([':attribute', ':value', ':oldValue'], [(string)$fieldTranslate, (string)$value, (string)$oldValue], $this->tpl)
                ]);
            }
        }
    }

    /**
     * 功   能：解析配置
     * 修改日期：2020/7/27
     *
     * @param array|string $config
     * @return array
     */
    protected function parseConfig($config)
    {
        if (is_string($config) || isset($config['text'])) {
            $fieldClass = FieldText::class;
        } elseif (isset($config['relevance'])) {
            $fieldClass = FieldRelevance::class;
        } else {
            $fieldClass = FieldDefault::class;
        }
        if (isset($config['value'])) {
            if (is_callable($config['value'], true)) {
                $valueClass = ValueCallback::class;
            } elseif (is_array($config['value'])) {
                $valueClass = ValueArray::class;
            }
        }
        isset($valueClass) || $valueClass = ValueDefault::class;
        return [$fieldClass, $valueClass];
    }

    /**
     * 功   能：翻译
     * 修改日期：2020/7/28
     *
     * @param array|null $data
     * @param array|null $oldData
     * @return $this
     * @throws TranslationException
     */
    public function translate(array $data = null, array $oldData = null)
    {
        if (!is_null($data)) {
            $this->setData($data);
        }
        if (!is_null($oldData)) {
            $this->setOldData($oldData);
        }
        foreach ($this->configs as $field => $config) {
            [$fieldClass, $valueClass] = $this->parseConfig($config);
            if (!class_exists($fieldClass)) {
                throw new TranslationException($fieldClass . '类不存在');
            }
            $fieldTranslator = new $fieldClass();
            if (!$fieldTranslator instanceof FieldTranslatorInterface) {
                throw new TranslationException("{$fieldClass}必须implements " . FieldTranslatorInterface::class);
            }
            if (!class_exists($valueClass)) {
                throw new TranslationException($valueClass . '类不存在');
            }
            $valueTranslator = new $valueClass();
            if (!$valueTranslator instanceof ValueTranslatorInterface) {
                throw new TranslationException("{$valueClass}必须implements " . ValueTranslatorInterface::class);
            }
            $fieldTranslator->translate($this, $config, $field);
            $valueTranslator->translate($this, $config, $field);
        }
        $this->assemble();
        return $this;
    }

    /**
     * 功   能：获取翻译后的字符串
     * 修改日期：2020/7/28
     *
     * @return string
     */
    public function getTranslateString()
    {
        return implode(';',  $this->getTranslates());
    }

    public function __toString()
    {
        return $this->getTranslateString();
    }
}
