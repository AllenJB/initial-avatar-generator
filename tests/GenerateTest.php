<?php
declare(strict_types=1);

use AllenJB\InitialAvatarGenerator\InitialAvatar;
use Imagine\Image\ImageInterface;
use PHPUnit\Framework\TestCase;
use SVG\SVG;

class GenerateTest extends TestCase
{
    /** @test */
    public function CanGenerateInitialsWithoutNameParameter()
    {
        $avatar = new InitialAvatar();

        $avatar->generate('Lasse Rafn');

        $this->assertEquals('LR', $avatar->getInitials());
    }


    /** @test */
    public function returns_image_object()
    {
        $avatar = new InitialAvatar();

        $image = $avatar->generate();

        $this->assertInstanceOf(ImageInterface::class, $image);
    }


    /** @test */
    public function returns_image_object_with_emoji()
    {
        $avatar = new InitialAvatar();

        $image = $avatar->generate('😅');

        $this->assertInstanceOf(ImageInterface::class, $image);
    }


    /** @test */
    public function returns_image_object_with_japanese_letters()
    {
        $avatar = new InitialAvatar();

        $image = $avatar->font(__DIR__ . '/fonts/NotoSans-Regular.otf')->generate('こんにちは');

        $this->assertInstanceOf(ImageInterface::class, $image);
    }


    /** @test */
    public function can_use_imagick_driver()
    {
        $avatar = new InitialAvatar();

        $image = $avatar->imagick()->generate('LR');

        $this->assertInstanceOf(ImageInterface::class, $image);
    }


    /** @test */
    public function can_use_gd_driver()
    {
        $avatar = new InitialAvatar();

        $image = $avatar->gd()->generate('LR');

        $this->assertInstanceOf(ImageInterface::class, $image);
    }


    /** @test */
    public function can_make_rounded_images()
    {
        $avatar = new InitialAvatar();

        $image = $avatar->rounded()->generate();

        $this->assertInstanceOf(ImageInterface::class, $image);
    }


    /** @test */
    public function can_make_a_smooth_rounded_image()
    {
        $avatar = new InitialAvatar();

        $image = $avatar->rounded()->smooth()->generate();

        $this->assertInstanceOf(ImageInterface::class, $image);
    }


    /** @test */
    public function can_use_local_font()
    {
        $avatar = new InitialAvatar();

        $image = $avatar->font(__DIR__ . '/fonts/NotoSans-Regular.otf')->generate();

        $this->assertInstanceOf(ImageInterface::class, $image);
    }


    /** @test */
    public function has_a_font_fallback()
    {
        $avatar = new InitialAvatar();

        $this->expectWarning();
        $this->expectWarningMessageMatches('/Could not find\/open font/');
        $image = $avatar->font('no-font')->generate();

        $this->assertInstanceOf(ImageInterface::class, $image);
    }


    /** @test */
    public function can_handle_fonts_without_slash_first()
    {
        $avatar = new InitialAvatar();

        $image = $avatar->font(__DIR__ .'/fonts/NotoSans-Regular.otf')->generate();

        $this->assertInstanceOf(ImageInterface::class, $image);
    }


    /** @test */
    public function can_render_svg()
    {
        $avatar = new InitialAvatar();

        $image = $avatar->generateSvg("AB");

        $this->assertInstanceOf(SVG::class, $image);
    }
}
