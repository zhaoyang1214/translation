<?php

namespace Snow\Translation\FieldTranslator;

use Snow\Translation\FieldTranslator;
use Snow\Translation\Translation;

class FieldDefault extends FieldTranslator
{
    public function translate(Translation $translation, $config, string $field)
    {
        $translation->setFieldTranslates($field, $field);
        $translation->setOldFieldTranslates($field, $field);
    }
}
