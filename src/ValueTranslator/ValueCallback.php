<?php

namespace Snow\Translation\ValueTranslator;

use Snow\Translation\Translation;
use Snow\Translation\ValueTranslator;

class ValueCallback extends ValueTranslator
{
    public function translate(Translation $translation, $config, string $field)
    {
        $data = $translation->getData();
        $oldData = $translation->getOldData();
        $value = $this->getValue($field, $data);
        $oldValue = $this->getValue($field, $oldData);
        $value = call_user_func($config['value'], $value, $field, $data);
        $oldValue = call_user_func($config['value'], $oldValue, $field, $oldData);
        isset($value) && $translation->setDataTranslates($field, $value);
        isset($oldValue) && $translation->setOldDataTranslates($field, $oldValue);
    }
}
