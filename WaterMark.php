<?php
class WaterMarking
{
    protected $watermark = 'C:\Users\jbell\Documents\git\Logo-Planter\Logo.png';

    private function PhotoConvert($file)
    {
        $fileName = explode(".", $file);
        if ($fileName[1] != "jpg" || $fileName[1] != "jpeg")
        {
            if (preg_match('/png/i', $fileName[1]))
            {
                $image = imagecreatefrompng($file);
            }
            elseif (preg_match('/gif/i', $fileName[1]))
            {
                $image = imagecreatefromgif($file);
            }
            else
            {
                $image = imagejpeg($file);
            }
            return $image;
        }
        else
        {
            return imagecreatefromjpeg($file);
        }
    }

    final function WaterMark($file)
    {
        if(is_readable($file))
        {
            $image = $this->PhotoConvert($file);
            $watermark = imagecreatefrompng($this->watermark);
            imagealphablending($watermark, false);
            imagesavealpha($watermark, true);
            $image_w = imagesx($image);
            $image_h = imagesy($image);
            $water_w = imagesx($watermark);
            $water_h = imagesy($watermark);
            $dstX = ($image_w / 2) - ($water_w / 2);
            $dstY = ($image_h / 2) - ($water_h / 2);
            imagecopy($image, $watermark, $dstX, $dstY, 0, 0, $water_w, $water_h);
            imagejpeg($image, 'C:\Users\jbell\Documents\git\Logo-Planter\New.jpg', 100);
            imagedestroy($image);
            imagedestroy($watermark);
            return true;
        }
        else
        {
            return false;
        }
    }
}

$logoMaker = new WaterMarking();
$upload = 'C:\Users\jbell\Documents\git\Logo-Planter\original.png';
if ($logoMaker->WaterMark($upload))
{
    print "Good.";
}
else
{
    print "failed";
}