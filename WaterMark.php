<?php
/*
 * TODO: Change:
 * watermark to your watermark file
 * upload to your upload file
 * upload to the original file path
 */
class WaterMarking
{
    // setup the static watermark file
    protected $watermark = 'LOGO PATH';
    protected $export = 'EXPORT FILE PATH';

    // transforms original import file into jpg format
    private function PhotoConvert($file)
    {
        // create array to get file type
        $fileName = explode(".", $file);
        // ensure we aren't transforming jpg to jpg
        if ($fileName[1] != "jpg" || $fileName[1] != "jpeg")
        {
            // regular expression matching for file type to convert it
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
                // just make a new image
                $image = imagejpeg($file);
            }
            return $image;
        }
        else
        {
            // if it is a jpg just return it as a resource
            return imagecreatefromjpeg($file);
        }
    }

    // actual add watermark
    final function WaterMark($file)
    {
        // if the file is readable
        if(is_readable($file))
        {
            // declare local variable
            $image = $this->PhotoConvert($file);
            // make watermark local
            $watermark = imagecreatefrompng($this->watermark);
            // alpha
            imagealphablending($watermark, false);
            imagesavealpha($watermark, true);
            // get parameters for image sizes
            $image_w = imagesx($image);
            $image_h = imagesy($image);
            $water_w = imagesx($watermark);
            $water_h = imagesy($watermark);
            // find exact center
            $dstX = ($image_w / 2) - ($water_w / 2);
            $dstY = ($image_h / 2) - ($water_h / 2);
            // paste watermark on image
            imagecopy($image, $watermark, $dstX, $dstY, 0, 0, $water_w, $water_h);
            // export new file
            imagejpeg($image, $this->export, 100);
            // destroy resources
            imagedestroy($image);
            imagedestroy($watermark);
            // indicate success
            return true;
        }
        else
        {
            // indicate failure
            return false;
        }
    }
}

//actually do the thing
$logoMaker = new WaterMarking();
$upload = 'ORIGINAL PATH';
if ($logoMaker->WaterMark($upload))
{
    print "Good.";
}
else
{
    print "failed";
}