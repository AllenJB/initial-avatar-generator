<?php

use AllenJB\InitialAvatarGenerator\InitialAvatar;
use PHPUnit\Framework\TestCase;

class ParameterTest extends TestCase
{
    /** @test */
    public function can_set_background_color()
    {
        $avatar = new InitialAvatar();

        $avatar->background('#000');

        $this->assertEquals('#000', $avatar->getBackgroundColor());

        $avatar->background('#fff');

        $this->assertEquals('#fff', $avatar->getBackgroundColor());
    }


    /** @test */
    public function can_set_font_color()
    {
        $avatar = new InitialAvatar();

        $avatar->color('#000');

        $this->assertEquals('#000', $avatar->getColor());

        $avatar->color('#fff');

        $this->assertEquals('#fff', $avatar->getColor());
    }


    /** @test */
    public function can_set_font_size()
    {
        $avatar = new InitialAvatar();

        $avatar->fontSize(0.3);

        $this->assertEquals(0.3, $avatar->getFontSize());

        $avatar->fontSize(0.7);

        $this->assertEquals(0.7, $avatar->getFontSize());
    }


    /** @test */
    public function can_set_font()
    {
        $avatar = new InitialAvatar();

        $avatar->font('/fonts/OpenSans-Semibold.ttf');

        $this->assertEquals('/fonts/OpenSans-Semibold.ttf', $avatar->getFontFile());
    }


    /** @test */
    public function can_set_rounded()
    {
        $avatar = new InitialAvatar();

        $avatar->rounded();

        $this->assertTrue($avatar->getRounded());

        $avatar->rounded(false);

        $this->assertNotTrue($avatar->getRounded());
    }


    /** @test */
    public function can_set_smooth()
    {
        $avatar = new InitialAvatar();

        $avatar->smooth();

        $this->assertTrue($avatar->getSmooth());

        $avatar->smooth(false);

        $this->assertNotTrue($avatar->getSmooth());
    }
}
