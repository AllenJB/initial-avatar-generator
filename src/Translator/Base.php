<?php

namespace AllenJB\InitialAvatarGenerator\Translator;

interface Base
{
    /**
     * Translate words to english
     */
    public function translate(string $words): string;


    /**
     * Get the source language of translator
     */
    public function getSourceLanguage(): string;
}