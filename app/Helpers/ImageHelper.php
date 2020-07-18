<?php
namespace App\Helpers;

use Intervention\Image\ImageManager;
use Illuminate\Http\Request;

class ImageHelper
{
    public function handleUploadedImage($path, $width = 240, $height = 240, $text = 'test', $size = 30, $positionX = 35, $positionY = 220)
    {
        $manager = new ImageManager(['driver' => 'gd']);
        
        $manager->make($this->getImagePath($path))
                ->resize($width, $height)
                ->save()
                ->destroy();

        $image = $manager->make($this->getImagePath($path));
        
        $mainColor = $this->getMainColor($image);

        $image = $manager->make($this->getImagePath($path));

        $this->setImageWatermark($image, $mainColor, $text, $size, $positionX, $positionY);
    }

    protected function getImagePath($path)
    {
        return public_path('storage/'.$path);
    }

    protected function getMainColor($image)
    {
        if(empty($image))
            return false;

        $countR = $countG = $countB = 0;
        
        $width = $image->width();
        $height = $image->height();

        for ($x = 0; $x < $width; $x++) {

            for ($y = 0; $y < $height; $y++) {

                $rgb = $image->pickColor($x, $y, 'int');
                $countR += ($rgb >> 16) & 0xFF;
                $countG += ($rgb >> 8) & 0xFF;
                $countB += $rgb & 0xFF;
            }
        }

        $image->destroy();
        
        // get the RGB values of the base color
        $mainR = round($countR / $width / $height);
        $mainG = round($countG / $width / $height);
        $mainB = round($countB / $width / $height);
        
        // convert RGB to HSV
        $valueR = ($mainR / 255);
        $valueG = ($mainG / 255);
        $valueB = ($mainB / 255);

        // get hue 
        $maxRGB = max(array($valueR, $valueG, $valueB));
        $minRGB = min(array($valueR, $valueG, $valueB));
        
        $delta = $maxRGB - $minRGB;

        if ($delta != 0) {
            
            if ($maxRGB == $valueR) {
                $h = (($valueG - $valueB) / $delta);
            }
            elseif ($maxRGB == $valueG) {
                $h = 2 + ($valueB - $valueR) / $delta;
            }
            elseif ($maxRGB == $valueB) {
                $h = 4 + ($valueR - $valueG) / $delta;
            }
            
            $hue = round($h * 60);
            
            if ($hue < 0) { 
                $hue += 360; 
            }
        }
        else {
            $hue = 0;
        }

        // get
        if ($hue > 320 || $hue <= 40) {
            $color = 'red';
        }
        elseif ($hue > 190 && $hue <= 260) {
            $color='blue';
        }
        elseif ($hue > 70 && $hue <= 175) {
            $color='green';
        }
        else {
            $color = 'yellow';
        }

        return $color;
    }

    protected function setImageWatermark($image, $color = "red", $text, $size, $positionX, $positionY)
    {   
        $textColor = '#ffffff';

        switch ($color) {
            case 'red':
                $textColor = '#000000';
                break;
            case 'blue':
                $textColor = '#ffff00';
                break;
            case 'green':
                $textColor = '#ff0000';
                break;
        }
        
        $image->text($text, $positionX, $positionY, function($font) use ($size, $textColor) {
                    $font->file(public_path('fonts/newRoman/11874.ttf'))
                         ->color($textColor)
                         ->size($size)
                         ->align('center')
                         ->valign('bottom');
                })
                ->save()
                ->destroy();
    }
}