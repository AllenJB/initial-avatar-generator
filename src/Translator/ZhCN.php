<?php

namespace AllenJB\InitialAvatarGenerator\Translator;

use Overtrue\Pinyin\Pinyin;

class ZhCN implements Base
{
    /**
     * @inheritdoc
     */
    public function translate(string $words): string
    {
        return Pinyin::name($words)->join(' ');
    }


    /**
     * @inheritdoc
     */
    public function getSourceLanguage(): string
    {
        return 'zh-CN';
    }
}