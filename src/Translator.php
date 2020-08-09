<?php

namespace Snow\Translation;

abstract class Translator
{
    /**
     * 功   能：从数据中获取值
     * 修改日期：2020/7/28
     *
     * @param string $field
     * @param array|string $data
     * @return array|mixed|string|null
     */
    protected function getValue(string $field, $data)
    {
        $fieldArr = explode('.', $field);
        $exists = true;
        foreach ($fieldArr as $attr) {
            if (is_array($data)) {
                if (!isset($data[$attr])) {
                    $exists = false;
                    break;
                }
                $data = $data[$attr];
                continue;
            } elseif (is_string($data)) {
                $dataTmp = json_decode($data, true);
                if (json_last_error() || !isset($dataTmp[$attr])) {
                    $exists = false;
                    break;
                }
                $data = $dataTmp[$attr];
                continue;
            }
            $exists = false;
            break;
        }
        return $exists ? $data : null;
    }

    abstract public function translate(Translation $translation, $config, string $field);
}
