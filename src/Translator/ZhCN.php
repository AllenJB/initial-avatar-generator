<?php

namespace LasseRafn\InitialAvatarGenerator\Translator;

use Overtrue\Pinyin\Pinyin;

class ZhCN implements Base
{
    /**
     * Inherent instance of zh-CN translator
     */
    protected Pinyin $inherent;


    /**
     * ZhCN constructor, set the instance of PinYin
     */
    public function __construct()
    {
        $this->inherent = new Pinyin();
    }


    /**
     * @inheritdoc
     */
    public function translate(string $words): string
    {
        return implode(' ', $this->inherent->name($words));
    }


    /**
     * @inheritdoc
     */
    public function getSourceLanguage(): string
    {
        return 'zh-CN';
    }
}