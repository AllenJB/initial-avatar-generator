# Generate avatars with initials
Fork of [lasserafn/php-initial-avatar-generator](https://github.com/LasseRafn/php-initial-avatar-generator) updated to
use [Imagine](https://github.com/php-imagine/Imagine), resolving issues with PHP 8.1

This library should be pretty much a drop-in replacement for most users with the following notable changes:

* This library uses strictly defined types
* This library returns Imagine objects instead for (non-SVG) generated avatars
* Font numbers in place of files were removed
* AutoFont functionality has been removed - you can reimplement this yourself using the lasserafn/php-string-script-language package
* Only the default Open Sans Regular font is shipped with the package
* FontAwesome files are no longer shipped with the library

## Installation
You just require using composer and you're good to go!
````bash
composer require allenjb/initial-avatar-generator
````

## Usage
As with installation, usage is quite simple. Generating a image is done by running:
````php
$avatar = new AllenJB\InitialAvatarGenerator\InitialAvatar();

$image = $avatar->name('Lasse Rafn')->generate();
````

Thats it! The method will return a instance of an [ImageInterface from Imagine](https://imagine.readthedocs.io/en/stable/_static/API/Imagine/Image/ImageInterface.html) so you can save or output the image:
````php
return $image->show('png');
````
If only one word is passed, the first 2 letters will be used. If more than 2 words are passed, the initials of the first and last words will be used.

## SVG generation
````php
$avatar = new AllenJB\InitialAvatarGenerator\InitialAvatar();

echo $avatar->name('Lasse Rafn')->generateSvg()->toXMLString(); // returns SVG XML string
````

## Supported methods and parameters

### Name (initials) - default: JD
````php
$image = $avatar->name('Albert Magnum')->generate();
````

### Width - default: 48
````php
// will be 96 pixels wide.
$image = $avatar->width(96)->generate();
````

### Height - default: 48
````php
// will be 96 pixels tall.
$image = $avatar->height(96)->generate();
````

### Size - default: 48 (proxy for `$avatar->width(X)->height(X)`)
````php
// will be 96x96 pixels.
$image = $avatar->size(96)->generate();
````

### Background color - default: #f0e9e9
````php
// will be red
$image = $avatar->background('#ff0000')->generate();
````

### Font color - default: #8b5d5d
````php
// will be red
$image = $avatar->color('#ff0000')->generate();
````

### Font file - default: /fonts/OpenSans-Regular.ttf
````php
// will be Semibold
$image = $avatar->font('/path/to/fonts/OpenSans-Semibold.ttf')->generate();
````

### Font name (for SVGs) - default: Open Sans, sans-serif

````php
$image = $avatar->fontName('Arial, Helvetica, sans-serif')->generate();
````

### Length - default: 2
````php
$image = $avatar->name('John Doe Johnson')->length(3)->generate(); // 3 letters = JDJ
````

### Switching driver - default: gd
````php
$image = $avatar->gd()->generate(); // Uses GD driver
$image = $avatar->imagick()->generate(); // Uses Imagick driver
````

### Rounded - default: false
````php
$image = $avatar->rounded()->generate();
````

### Smooth - default: false

Makes rounding smoother with a resizing hack. Could be slower.

````php
$image = $avatar->rounded()->smooth()->generate();
````

Alternatively consider using CSS instead.

### Font Size - default: 0.5
````php
$image = $avatar->fontSize(0.25)->generate(); // Font will be 25% of image size.
````
If the Image size is 50px and fontSize is 0.5, the font size will be 25px.

## Chaining it all together

````php
return $avatar->name('Lasse Rafn')
              ->length(2)
              ->fontSize(0.5)
              ->size(96) // 48 * 2
              ->background('#8BC34A')
              ->color('#fff')
              ->generate()
              ->save('png');
````

Now, using that in a image (sized 48x48 pixels for retina):
````html
<img src="url-for-avatar-generation" width="48" height="48" style="border-radius: 100%" />
````
Will yield:

<img src="https://raw.githubusercontent.com/AllenJB/initial-avatar-generator/master/demo_result.png" width="48" height="48" alt="Result" style="border-radius: 100%" />

*Rounded for appearance; the actual avatar is a filled square*

## Icon Font Support

First, you need to "find" the respective unicode for the glyph you want to insert. For example, using FontAwesome, to display a typical "user" icon use unicode: `f007`. You can usually find the unicode character (code) on the icon font browser.

An example for rendering a red avatar with a white "user" glyph would look like this:

```php
// note that this code
// 1) uses glyph() instead of name
// 2) changes the font to FontAwesome!
return $avatar->glyph('f007')
              ->font('/path/to/fonts/FontAwesome5Free-Regular-400.otf')
              ->color('#fff')
              ->background('#ff0000')
              ->generate()
              ->save('png');
```
