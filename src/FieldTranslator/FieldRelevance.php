<?php

namespace Snow\Translation\FieldTranslator;

use Snow\Translation\FieldTranslator;
use Snow\Translation\Translation;

class FieldRelevance extends FieldTranslator
{
    public function relevance(Translation $translation, $relevance, $data)
    {
        if (empty($relevance)) {
            return false;
        }
        $config = reset($relevance);
        $filed = key($relevance);
        $value = $this->getValue($filed, $data);
        if (isset($config[$value])) {
            if (is_string($config[$value])) {
                return $config[$value];
            } elseif (isset($config[$value]['relevance'])) {
                return $this->relevance($translation, $config[$value]['relevance'], $data);
            }
        }
        return false;
    }

    public function translate(Translation $translation, $config, string $field)
    {
        $filedText = $this->relevance($translation, $config['relevance'], $translation->getData());
        if ($filedText === false || $filedText === '') {
            $filedText = $field;
        }
        $translation->setFieldTranslates($field, $filedText);
        $oldFiledText = $this->relevance($translation, $config['relevance'], $translation->getOldData());
        if ($oldFiledText === false || $oldFiledText === '') {
            $oldFiledText = $field;
        }
        $translation->setOldFieldTranslates($field, $oldFiledText);
    }
}
