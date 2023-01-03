<?php

use AllenJB\InitialAvatarGenerator\InitialAvatar;
use PHPUnit\Framework\TestCase;

class ImageSizeTest extends TestCase
{
    /** @test */
    public function can_set_image_size_to_50_pixels()
    {
        $avatar = new InitialAvatar();

        $avatar->size(50);

        $this->assertEquals(50, $avatar->generate()->getSize()->getWidth());
        $this->assertEquals(50, $avatar->generate()->getSize()->getHeight());
    }


    /** @test */
    public function can_set_image_size_to_100_pixels()
    {
        $avatar = new InitialAvatar();

        $avatar->size(100);

        $this->assertEquals(100, $avatar->generate()->getSize()->getWidth());
        $this->assertEquals(100, $avatar->generate()->getSize()->getHeight());
    }
}
