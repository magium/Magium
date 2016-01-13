<?php

namespace Magium\Util\Translator;

interface TranslatorAware
{
    public function setTranslator(Translator $translator);
}