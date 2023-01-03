<?php

namespace AllenJB\InitialAvatarGenerator;

use Imagick;
use Imagine\Gd\Font as ImagineGdFont;
use Imagine\Gd\Imagine as ImagineGd;
use Imagine\Image\AbstractFont;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Palette\Color\ColorInterface;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;
use Imagine\Imagick\Font as ImagineImagickFont;
use Imagine\Imagick\Imagine as ImagineImagick;
use AllenJB\InitialAvatarGenerator\Translator\Base;
use AllenJB\InitialAvatarGenerator\Translator\En;
use AllenJB\InitialAvatarGenerator\Translator\ZhCN;
use LasseRafn\Initials\Initials;
use LasseRafn\StringScript;
use SVG\Nodes\Shapes\SVGCircle;
use SVG\Nodes\Shapes\SVGRect;
use SVG\Nodes\Texts\SVGText;
use SVG\SVG;
use UnexpectedValueException;

class InitialAvatar
{
    protected ImagineInterface $imagine;

    protected Initials $initials_generator;

    protected string $driver = 'gd'; // imagick or gd

    protected float $fontSize = 0.5;

    protected string $name = 'John Doe';

    protected int $width = 48;

    protected int $height = 48;

    protected string $bgColor = '#f0e9e9';

    protected string $fontColor = '#8b5d5d';

    protected bool $rounded = false;

    protected bool $smooth = false;

    protected bool $autofont = false;

    protected bool $keepCase = false;

    protected bool $allowSpecialCharacters = true;

    protected string $fontFile = '/fonts/OpenSans-Regular.ttf';

    protected string $fontName = 'OpenSans, sans-serif';

    protected string $generated_initials = 'JD';

    protected bool $preferBold = false;

    /**
     * Language eg.en zh-CN
     */
    protected string $language = 'en';

    /**
     * Role translator
     */
    protected Base $translator;

    /**
     * @var array<string, class-string<Base>> $translatorMap Language related to translator
     */
    protected array $translatorMap = [
        'en' => En::class,
        'zh-CN' => ZhCN::class,
    ];


    public function __construct()
    {
        $this->setupImageManager();
        $this->initials_generator = new Initials();
    }


    protected function setupImageManager(): void
    {
        switch ($this->driver) {
            case 'gd':
                $this->imagine = new ImagineGd();
                break;

            case 'imagick':
                $this->imagine = new ImagineImagick();
                break;

            default:
                throw new UnexpectedValueException("Unrecognized driver name. Must be 'gd' or 'imagick'");
        }
    }


    /**
     * Set the name used for generating initials.
     */
    public function name(string $nameOrInitials): self
    {
        $nameOrInitials = $this->translate($nameOrInitials);
        $this->name = $nameOrInitials;
        $this->initials_generator->name($nameOrInitials);

        return $this;
    }


    /**
     * Transforms a unicode string to the proper format
     *
     * @param string $char the code to be converted (e.g., f007 would mean the "user" symbol)
     */
    public function glyph(string $char): self
    {
        $uChar = json_decode(sprintf('"\u%s"', $char), false);
        if (! is_string($uChar)) {
            throw new \UnexpectedValueException("Failed to transform unicode character");
        }
        $this->name($uChar);

        return $this;
    }


    /**
     * Set the length of the generated initials.
     */
    public function length(int $length = 2): self
    {
        $this->initials_generator->length($length);

        return $this;
    }


    /**
     * Set the avatar/image size in pixels.
     */
    public function size(int $size): self
    {
        $this->width = $size;
        $this->height = $size;

        return $this;
    }


    /**
     * Set the avatar/image height in pixels.
     */
    public function height(int $height): self
    {
        $this->height = $height;

        return $this;
    }


    /**
     * Set the avatar/image width in pixels.
     */
    public function width(int $width): self
    {
        $this->width = $width;

        return $this;
    }


    /**
     * Prefer bold fonts (if possible)
     */
    public function preferBold(): self
    {
        $this->preferBold = true;

        return $this;
    }


    /**
     * Prefer regular fonts (if possible)
     */
    public function preferRegular(): self
    {
        $this->preferBold = false;

        return $this;
    }


    /**
     * Set the background color.
     */
    public function background(string $background): self
    {
        $this->bgColor = $background;

        return $this;
    }


    /**
     * Set the font color.
     */
    public function color(string $color): self
    {
        $this->fontColor = $color;

        return $this;
    }


    /**
     * Set the font file by path
     */
    public function font(string $font): self
    {
        $this->fontFile = $font;

        return $this;
    }


    /**
     * Set the font name
     *
     * Example: "Open Sans"
     */
    public function fontName(string $name): self
    {
        $this->fontName = $name;

        return $this;
    }


    /**
     * Use imagick as the driver.
     */
    public function imagick(): self
    {
        $this->driver = 'imagick';

        $this->setupImageManager();

        return $this;
    }


    /**
     * Use GD as the driver.
     */
    public function gd(): self
    {
        $this->driver = 'gd';

        $this->setupImageManager();

        return $this;
    }


    /**
     * Set if should make a round image or not.
     */
    public function rounded(bool $rounded = true): self
    {
        $this->rounded = $rounded;

        return $this;
    }


    /**
     * Set if should detect character script
     * and use a font that supports it.
     */
    public function autoFont(bool $autofont = true): self
    {
        $this->autofont = $autofont;

        return $this;
    }


    /**
     * Set if should make a rounding smoother with a resizing hack.
     */
    public function smooth(bool $smooth = true): self
    {
        $this->smooth = $smooth;

        return $this;
    }


    /**
     * Set if should skip uppercasing the name.
     */
    public function keepCase(bool $keepCase = true): self
    {
        $this->keepCase = $keepCase;

        return $this;
    }


    /**
     * Set if should allow (or remove) special characters
     */
    public function allowSpecialCharacters(bool $allowSpecialCharacters = true): self
    {
        $this->allowSpecialCharacters = $allowSpecialCharacters;

        return $this;
    }


    /**
     * Set the font size in percentage
     * (0.1 = 10%).
     */
    public function fontSize(float $size = 0.5): self
    {
        $this->fontSize = $size;

        return $this;
    }


    /**
     * Generate the image.
     */
    public function generate(?string $name = null): ImageInterface
    {
        if ($name !== null) {
            $this->name = $name;
            $this->generated_initials = $this->initials_generator->keepCase($this->getKeepCase())
                ->allowSpecialCharacters($this->getAllowSpecialCharacters())
                ->generate($name);
        }

        return $this->makeAvatar();
    }


    /**
     * Generate the image.
     */
    public function generateSvg(?string $name = null): SVG
    {
        if ($name !== null) {
            $this->name = $name;
            $this->generated_initials = $this->initials_generator->keepCase($this->getKeepCase())
                ->allowSpecialCharacters($this->getAllowSpecialCharacters())
                ->generate($name);
        }

        return $this->makeSvgAvatar();
    }


    /**
     * Will return the generated initials.
     */
    public function getInitials(): string
    {
        return $this->initials_generator->keepCase($this->getKeepCase())
            ->allowSpecialCharacters($this->getAllowSpecialCharacters())
            ->name($this->name)
            ->getInitials();
    }


    /**
     * Will return the background color parameter.
     */
    public function getBackgroundColor(): string
    {
        return $this->bgColor;
    }


    /**
     * Will return the set driver.
     */
    public function getDriver(): string
    {
        return $this->driver;
    }


    /**
     * Will return the font color parameter.
     */
    public function getColor(): string
    {
        return $this->fontColor;
    }


    /**
     * Will return the font size parameter.
     */
    public function getFontSize(): float
    {
        return $this->fontSize;
    }


    /**
     * Will return the font file parameter.
     */
    public function getFontFile(): string
    {
        return $this->fontFile;
    }


    /**
     * Will return the font name parameter for SVGs.
     *
     * @return string
     */
    public function getFontName(): string
    {
        return $this->fontName;
    }


    /**
     * Will return the round parameter.
     */
    public function getRounded(): bool
    {
        return $this->rounded;
    }


    /**
     * Will return the smooth parameter.
     */
    public function getSmooth(): bool
    {
        return $this->smooth;
    }


    /**
     * Will return the width parameter.
     */
    public function getWidth(): int
    {
        return $this->width;
    }


    /**
     * Will return the height parameter.
     */
    public function getHeight(): int
    {
        return $this->height;
    }


    /**
     * Will return the keepCase parameter.
     */
    public function getKeepCase(): bool
    {
        return $this->keepCase;
    }


    /**
     * Will return the allowSpecialCharacters parameter.
     */
    public function getAllowSpecialCharacters(): bool
    {
        return $this->allowSpecialCharacters;
    }


    /**
     * Will return the autofont parameter.
     */
    public function getAutoFont(): bool
    {
        return $this->autofont;
    }


    /**
     * Set language of name, pls use `language` before `name`, just like
     * ```php
     * $avatar->language('en')->name('Mr Green'); // Right
     * $avatar->name('Mr Green')->language('en'); // Wrong
     * ```
     */
    public function language(string $language): self
    {
        $this->language = $language ?: 'en';

        return $this;
    }


    /**
     * Add new translators designed by user
     *
     * @param array<string, class-string<Base>> $translatorMap
     *     ```php
     *     $translatorMap = [
     *     'fr' => 'foo\bar\Fr',
     *     'zh-TW' => 'foo\bar\ZhTW'
     *     ];
     *     ```
     */
    public function addTranslators(array $translatorMap): self
    {
        $this->translatorMap = array_merge($this->translatorMap, $translatorMap);

        return $this;
    }


    protected function translate(string $nameOrInitials): string
    {
        return $this->getTranslator()->translate($nameOrInitials);
    }


    /**
     * Instance the translator by language
     */
    protected function getTranslator(): Base
    {
        if (isset($this->translator) && ($this->translator->getSourceLanguage() === $this->language)) {
            return $this->translator;
        }

        $translatorClass = ($this->translatorMap[$this->language] ?? En::class);

        $this->translator = new $translatorClass();
        return $this->translator;
    }


    protected function getImagineFont(string $file, int $size, ColorInterface $color): AbstractFont
    {
        switch ($this->driver) {
            case 'gd':
                return new ImagineGdFont($file, $size, $color);

            case 'imagick':
                return new ImagineImagickFont(new Imagick(), $file, $size, $color);

            default:
                throw new UnexpectedValueException("Unhandled driver: ". $this->driver);
        }
    }


    protected function makeAvatar(): ImageInterface
    {
        $width = $this->getWidth();
        $height = $this->getHeight();
        if ($this->getRounded() && $this->getSmooth()) {
            $width *= 5;
            $height *= 5;
        }

        $palette = new RGB();
        $bgColor = $palette->color($this->bgColor);
        if ($this->rounded) {
            $bgColor = $palette->color('#ffffff', 0);
        }
        $avatar = $this->imagine->create(new Box($width, $height), $bgColor);

        if ($this->rounded) {
            $avatar->draw()->circle(
                new Point($width / 2, $height / 2),
                (($width - 2) / 2),
                $palette->color($this->bgColor)
            );

            if ($this->smooth) {
                $width /= 5;
                $height /= 5;
                $avatar->resize(new Box($width, $height));
            }
        }

        $avatarText = $this->getInitials();

        $fontFile = $this->findFontFile();
        $font = $this->getImagineFont($fontFile, (int) ($this->fontSize * $width), $palette->color($this->fontColor));

        $textBox = $font->box($avatarText);
        $textBoxCenter = new Point\Center($textBox);
        $imageBoxCenter = new Point\Center($avatar->getSize());
        $centeredTextPosition = new Point(
            max($imageBoxCenter->getX() - $textBoxCenter->getX(), 0),
            max($imageBoxCenter->getY() - $textBoxCenter->getY(), 0)
        );

        $avatar->draw()->text($this->getInitials(), $font, $centeredTextPosition);

        return $avatar;
    }


    protected function makeSvgAvatar(): SVG
    {
        // Original document
        $image = new SVG($this->getWidth(), $this->getHeight());
        $document = $image->getDocument();

        // Background
        if ($this->getRounded()) {
            // Circle
            $background = new SVGCircle($this->getWidth() / 2, $this->getHeight() / 2, $this->getWidth() / 2);
        } else {
            // Rectangle
            $background = new SVGRect(0, 0, $this->getWidth(), $this->getHeight());
        }

        $background->setStyle('fill', $this->getBackgroundColor());
        $document->addChild($background);

        SVG::addFont($this->findFontFile());
        // Text
        $text = new SVGText($this->getInitials(), '50%', '50%');
        $text->setFontFamily($this->getFontName());
        $text->setStyle('line-height', 1);
        $text->setAttribute('dy', '.1em');
        $text->setAttribute('fill', $this->getColor());
        $text->setAttribute('font-size', $this->getFontSize() * $this->getWidth());
        $text->setAttribute('text-anchor', 'middle');
        $text->setAttribute('dominant-baseline', 'middle');

        if ($this->preferBold) {
            $text->setStyle('font-weight', 600);
        }

        $document->addChild($text);

        return $image;
    }


    protected function findFontFile(): string
    {
        $fontFile = $this->getFontFile();

        if ($this->getAutoFont()) {
            $fontFile = $this->getFontByScript();
        }

        $weightsToTry = ['Regular'];

        if ($this->preferBold) {
            $weightsToTry = ['Bold', 'Semibold', 'Regular'];
        }

        $originalFile = $fontFile;

        foreach ($weightsToTry as $weight) {
            $fontFile = preg_replace('/(\-(Bold|Semibold|Regular))/', "-{$weight}", $originalFile);
            if ($fontFile === null) {
                throw new UnexpectedValueException("Failed to replace font weight");
            }

            if (file_exists($fontFile)) {
                return $fontFile;
            }

            if (file_exists(__DIR__ . $fontFile)) {
                return __DIR__ . $fontFile;
            }

            if (file_exists(__DIR__ . '/' . $fontFile)) {
                return __DIR__ . '/' . $fontFile;
            }
        }

        trigger_error("Font file not found: ". $fontFile, E_USER_WARNING);
        return __DIR__ .'/fonts/NotoSans-Regular.ttf';
    }


    protected function getFontByScript(): string
    {
        // Arabic
        if (StringScript::isArabic($this->getInitials())) {
            return __DIR__ . '/fonts/script/Noto-Arabic-Regular.ttf';
        }

        // Armenian
        if (StringScript::isArmenian($this->getInitials())) {
            return __DIR__ . '/fonts/script/Noto-Armenian-Regular.ttf';
        }

        // Bengali
        if (StringScript::isBengali($this->getInitials())) {
            return __DIR__ . '/fonts/script/Noto-Bengali-Regular.ttf';
        }

        // Georgian
        if (StringScript::isGeorgian($this->getInitials())) {
            return __DIR__ . '/fonts/script/Noto-Georgian-Regular.ttf';
        }

        // Hebrew
        if (StringScript::isHebrew($this->getInitials())) {
            return __DIR__ . '/fonts/script/Noto-Hebrew-Regular.ttf';
        }

        // Mongolian
        if (StringScript::isMongolian($this->getInitials())) {
            return __DIR__ . '/fonts/script/Noto-Mongolian-Regular.ttf';
        }

        // Thai
        if (StringScript::isThai($this->getInitials())) {
            return __DIR__ . '/fonts/script/Noto-Thai-Regular.ttf';
        }

        // Tibetan
        if (StringScript::isTibetan($this->getInitials())) {
            return __DIR__ . '/fonts/script/Noto-Tibetan-Regular.ttf';
        }

        // Chinese & Japanese
        if (StringScript::isJapanese($this->getInitials()) || StringScript::isChinese($this->getInitials())) {
            return __DIR__ . '/fonts/script/Noto-CJKJP-Regular.otf';
        }

        return $this->getFontFile();
    }
}
