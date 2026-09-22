<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class CaptchaController extends Controller
{
    public function image(): Response
    {
        /*
        |--------------------------------------------------------------------------
        | CAPTCHA TEXT
        |--------------------------------------------------------------------------
        */

        $characters = '23456789bcdfghjkmnpqrstvwxyz';

        $captcha = '';

        for ($i = 0; $i < 6; $i++) {
            $captcha .= $characters[
                random_int(0, strlen($characters) - 1)
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Store CAPTCHA in Session
        |--------------------------------------------------------------------------
        */

        session([
            'captcha_code' => $captcha,
        ]);

        /*
        |--------------------------------------------------------------------------
        | CAPTCHA IMAGE SIZE
        |--------------------------------------------------------------------------
        */

        $width  = 160;
        $height = 42;

        $image = imagecreatetruecolor(
            $width,
            $height
        );

        /*
        |--------------------------------------------------------------------------
        | COLORS
        |--------------------------------------------------------------------------
        */

        $backgroundColor = imagecolorallocate(
            $image,
            255,
            255,
            255
        );

        $textColor = imagecolorallocate(
            $image,
            22,
            36,
            83
        );

        $noiseColor = imagecolorallocate(
            $image,
            190,
            195,
            205
        );

        /*
        |--------------------------------------------------------------------------
        | WHITE BACKGROUND
        |--------------------------------------------------------------------------
        */

        imagefill(
            $image,
            0,
            0,
            $backgroundColor
        );

        /*
        |--------------------------------------------------------------------------
        | NOISE LINES
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < 2; $i++) {

            imageline(
                $image,
                random_int(0, $width),
                random_int(5, $height - 5),
                random_int(0, $width),
                random_int(5, $height - 5),
                $noiseColor
            );
        }

        /*
        |--------------------------------------------------------------------------
        | NOISE DOTS
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < 8; $i++) {

            imagefilledellipse(
                $image,
                random_int(0, $width),
                random_int(0, $height),
                2,
                2,
                $noiseColor
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FONT
        |--------------------------------------------------------------------------
        */

        $font = public_path(
            'frontend/fonts/fontawesome/webfonts/monofont.ttf'
        );

        /*
        |--------------------------------------------------------------------------
        | RENDER LARGE CAPTCHA TEXT
        |--------------------------------------------------------------------------
        */

        if (file_exists($font)) {

            /*
            | Base font size.
            | We will additionally scale the rendered text.
            */
            $fontSize = 38;

            $angle = 0;

            /*
            |--------------------------------------------------------------------------
            | Get Text Bounding Box
            |--------------------------------------------------------------------------
            */

            $box = imagettfbbox(
                $fontSize,
                $angle,
                $font,
                $captcha
            );

            $minX = min(
                $box[0],
                $box[2],
                $box[4],
                $box[6]
            );

            $maxX = max(
                $box[0],
                $box[2],
                $box[4],
                $box[6]
            );

            $minY = min(
                $box[1],
                $box[3],
                $box[5],
                $box[7]
            );

            $maxY = max(
                $box[1],
                $box[3],
                $box[5],
                $box[7]
            );

            $textWidth = $maxX - $minX;
            $textHeight = $maxY - $minY;

            /*
            |--------------------------------------------------------------------------
            | Create Transparent Text Image
            |--------------------------------------------------------------------------
            */

            $padding = 8;

            $textImageWidth =
                $textWidth + ($padding * 2);

            $textImageHeight =
                $textHeight + ($padding * 2);

            $textImage = imagecreatetruecolor(
                $textImageWidth,
                $textImageHeight
            );

            /*
            | Enable transparency
            */

            imagealphablending(
                $textImage,
                false
            );

            imagesavealpha(
                $textImage,
                true
            );

            $transparent = imagecolorallocatealpha(
                $textImage,
                255,
                255,
                255,
                127
            );

            imagefill(
                $textImage,
                0,
                0,
                $transparent
            );

            imagealphablending(
                $textImage,
                true
            );

            /*
            |--------------------------------------------------------------------------
            | Draw Text on Transparent Image
            |--------------------------------------------------------------------------
            */

            imagettftext(
                $textImage,
                $fontSize,
                $angle,
                $padding - $minX,
                $padding - $minY,
                $textColor,
                $font,
                $captcha
            );

            /*
            |--------------------------------------------------------------------------
            | Calculate Scale
            |--------------------------------------------------------------------------
            |
            | This is the important part.
            |
            | The monofont glyph itself is visually small, so we
            | enlarge the rendered bitmap before placing it.
            |
            */

            $availableWidth = $width - 16;
            $availableHeight = $height - 10;

            $scaleX =
                $availableWidth / $textImageWidth;

            $scaleY =
                $availableHeight / $textImageHeight;

            /*
            | Slightly enlarge the text.
            */
            $scale = min(
                $scaleX,
                $scaleY
            );

            /*
            | Extra enlargement.
            */
            $scale *= 1.15;

            /*
            | Prevent excessive size.
            */
            $scale = min(
                $scale,
                2.0
            );

            $newWidth = (int) (
                $textImageWidth * $scale
            );

            $newHeight = (int) (
                $textImageHeight * $scale
            );

            /*
            |--------------------------------------------------------------------------
            | Create Scaled Text Image
            |--------------------------------------------------------------------------
            */

            $scaledText = imagecreatetruecolor(
                $newWidth,
                $newHeight
            );

            imagealphablending(
                $scaledText,
                false
            );

            imagesavealpha(
                $scaledText,
                true
            );

            $transparent2 = imagecolorallocatealpha(
                $scaledText,
                255,
                255,
                255,
                127
            );

            imagefill(
                $scaledText,
                0,
                0,
                $transparent2
            );

            /*
            |--------------------------------------------------------------------------
            | Scale Text
            |--------------------------------------------------------------------------
            */

            imagecopyresampled(
                $scaledText,
                $textImage,
                0,
                0,
                0,
                0,
                $newWidth,
                $newHeight,
                $textImageWidth,
                $textImageHeight
            );

            /*
            |--------------------------------------------------------------------------
            | Center Scaled Text
            |--------------------------------------------------------------------------
            */

            $textX = (int) (
                ($width - $newWidth) / 2
            );

            $textY = (int) (
                ($height - $newHeight) / 2
            );

            /*
            |--------------------------------------------------------------------------
            | Copy Text to Main CAPTCHA Image
            |--------------------------------------------------------------------------
            */

            imagecopy(
                $image,
                $scaledText,
                $textX,
                $textY,
                0,
                0,
                $newWidth,
                $newHeight
            );

            /*
            |--------------------------------------------------------------------------
            | Destroy Temporary Images
            |--------------------------------------------------------------------------
            */

            imagedestroy(
                $textImage
            );

            imagedestroy(
                $scaledText
            );

        } else {

            /*
            |--------------------------------------------------------------------------
            | Fallback
            |--------------------------------------------------------------------------
            */

            imagestring(
                $image,
                5,
                40,
                20,
                $captcha,
                $textColor
            );
        }

        /*
        |--------------------------------------------------------------------------
        | OUTPUT JPEG
        |--------------------------------------------------------------------------
        */

        ob_start();

        imagejpeg(
            $image,
            null,
            95
        );

        $imageData = ob_get_clean();

        imagedestroy(
            $image
        );

        return response(
            $imageData,
            200,
            [
                'Content-Type' =>
                    'image/jpeg',

                'Cache-Control' =>
                    'no-store, no-cache, must-revalidate, max-age=0',

                'Pragma' =>
                    'no-cache',

                'Expires' =>
                    '0',
            ]
        );
    }
}
