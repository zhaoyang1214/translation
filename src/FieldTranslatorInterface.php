<?php


namespace Snow\Translation;


interface FieldTranslatorInterface
{
    /**
     * 功    能：翻译
     * 修改日期：2020/7/27
     *
     * @param Translation $translation
     * @param array|string $config
     * @param string $field
     * @return mixed
     */
    public function translate(Translation $translation, $config, string $field);
}