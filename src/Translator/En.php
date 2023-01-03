<?php

namespace AllenJB\InitialAvatarGenerator\Translator;

class En implements Base
{
    /**
     * @inheritdoc
     */
    public function translate(string $words): string
    {
        return $words;
    }


    /**
     * @inheritdoc
     */
    public function getSourceLanguage(): string
    {
        return 'en';
    }
}