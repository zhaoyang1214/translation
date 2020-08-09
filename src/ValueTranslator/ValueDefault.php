<?php

namespace Snow\Translation\ValueTranslator;

use Snow\Translation\Translation;
use Snow\Translation\ValueTranslator;

class ValueDefault extends ValueTranslator
{
    public function translate(Translation $translation, $config, string $field)
    {
        $value = $this->getValue($field, $translation->getData());
        $oldValue = $this->getValue($field, $translation->getOldData());
        isset($value) && $translation->setDataTranslates($field, $value);
        isset($oldValue) && $translation->setOldDataTranslates($field, $oldValue);
    }
}
