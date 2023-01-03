<?php

use AllenJB\InitialAvatarGenerator\InitialAvatar;
use Imagine\Image\ImageInterface;
use PHPUnit\Framework\TestCase;

class ScriptLanguageDetectionTest extends TestCase
{
    /** @test */
    public function can_detect_and_use_script_Arabic()
    {
        $avatar = new InitialAvatar();

        $image = $avatar->autoFont()->generate('الحزمة');

        $this->assertInstanceOf(ImageInterface::class, $image);
    }


    /** @test */
    public function can_detect_and_use_script_Armenian()
    {
        $avatar = new InitialAvatar();

        $image = $avatar->autoFont()->generate('բենգիմžē');

        $this->assertInstanceOf(ImageInterface::class, $image);
    }


    /** @test */
    public function can_detect_and_use_script_Bengali()
    {
        $avatar = new InitialAvatar();

        $image = $avatar->autoFont()->generate('ǰǰô জ');

        $this->assertInstanceOf(ImageInterface::class, $image);
    }


    /** @test */
    public function can_detect_and_use_script_Georgian()
    {
        $avatar = new InitialAvatar();

        $image = $avatar->autoFont()->generate('გამარჯობა');

        $this->assertInstanceOf(ImageInterface::class, $image);
    }


    /** @test */
    public function can_detect_and_use_script_Hebrew()
    {
        $avatar = new InitialAvatar();

        $image = $avatar->autoFont()->generate('ה ו ז ח ט');

        $this->assertInstanceOf(ImageInterface::class, $image);
    }


    /** @test */
    public function can_detect_and_use_script_Mongolian()
    {
        $avatar = new InitialAvatar();

        $image = $avatar->autoFont()->generate('ᠪᠣᠯᠠᠢ᠃');

        $this->assertInstanceOf(ImageInterface::class, $image);
    }


    /** @test */
    public function can_detect_and_use_script_Thai()
    {
        $avatar = new InitialAvatar();

        $image = $avatar->autoFont()->generate('สวัสดีชาวโลกและยินดีต้อนรับแพ็กเกจนี้');

        $this->assertInstanceOf(ImageInterface::class, $image);
    }


    /** @test */
    public function can_detect_and_use_script_Tibetan()
    {
        $avatar = new InitialAvatar();

        $image = $avatar->autoFont()->generate('ཀཁཆཇའ');

        $this->assertInstanceOf(ImageInterface::class, $image);
    }


    /** @test */
    public function can_detect_and_use_script_Uncommon()
    {
        $avatar = new InitialAvatar();

        $image = $avatar->autoFont()->generate('ψψ');

        $this->assertInstanceOf(ImageInterface::class, $image);
    }
}
