<?php

namespace Subapp\WebApp\Util;

/**
 * Class Image
 *
 * @package Subapp\Webapp\Util
 */
class Image
{
    
    const WATERMARK_TILE   = 1;
    const WATERMARK_RB     = 2;
    const WATERMARK_LB     = 4;
    const WATERMARK_RT     = 8;
    const WATERMARK_LT     = 16;
    const WATERMARK_MIDDLE = 32;
    
    /**
     * @var resource
     */
    private $image;
    
    /**
     * @var resource
     */
    private $original;
    
    /**
     * @var resource
     */
    private $watermark;
    
    /**
     * @param $filename
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function load($filename)
    {
        if (!file_exists($filename)) {
            throw new \InvalidArgumentException(sprintf('Unable to load image. File not found %s', $filename));
        }
        
        $imageInfo = getimagesize($filename);
        
        switch ($imageInfo[2]) {
            case IMAGETYPE_JPEG:
                $this->image = imagecreatefromjpeg($filename);
                break;
            case IMAGETYPE_GIF:
                $this->image = imagecreatefromgif($filename);
                break;
            case IMAGETYPE_PNG:
                $this->image = imagecreatefrompng($filename);
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Unable to load image. Unknown file type %s', $imageInfo[2]));
                break;
        }
        
        $this->original = $this->image;
        
        return $this;
    }
    
    /**
     * @return $this
     */
    public function resetToOriginal()
    {
        if (is_resource($this->original)) {
            $this->image = $this->original;
        }
        
        return $this;
    }
    
    /**
     * @param $filename
     * @return $this
     */
    public function loadWatermark($filename)
    {
        if (!file_exists($filename)) {
            throw new \InvalidArgumentException(sprintf('Unable to load watermark image. File not found %s', $filename));
        }
        
        $watermarkInfo = getimagesize($filename);
        
        if ($watermarkInfo[2] == IMAGETYPE_PNG) {
            $this->watermark = imagecreatefrompng($filename);
        } else {
            throw new \InvalidArgumentException('Unable to load watermark image. Allowed only PNG');
        }
        
        return $this;
    }
    
    /**
     * @param int $imagetype
     * @return $this
     */
    public function output($imagetype = IMAGETYPE_JPEG)
    {
        switch ($imagetype) {
            case IMAGETYPE_JPEG:
                header('Content-type: image/jpeg');
                imagejpeg($this->image);
                break;
            case IMAGETYPE_GIF:
                header('Content-type: image/gif');
                imagegif($this->image);
                break;
            case IMAGETYPE_PNG:
                header('Content-type: image/png');
                imagepng($this->image);
                break;
            default:
                header('Content-type: image/jpeg');
                imagejpeg($this->image);
                break;
        }
        
        return $this;
    }
    
    /**
     * @param     $filename
     * @param int $imagetype
     * @param int $quality
     * @param int $permission
     * @param int $own
     * @return $this
     * @throws \Exception
     */
    public function save($filename, $imagetype = IMAGETYPE_JPEG, $quality = 50, $permission = 0777, $own = -1)
    {
        $dirname = dirname($filename);
        
        if (!file_exists($dirname)) {
            @ mkdir($dirname, 0777, true);
        }
        
        switch ($imagetype) {
            case IMAGETYPE_JPEG:
                imagejpeg($this->image, $filename, $quality);
                break;
            case IMAGETYPE_GIF:
                imagegif($this->image, $filename);
                break;
            case IMAGETYPE_PNG:
                imagepng($this->image, $filename);
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Unable to save image. Unknown file type %s', $imagetype));
                break;
        }
        
        if (0 < $permission) {
            @ chmod($filename, $permission);
        }
        
        if (is_string($own)) {
            @ chown($filename, $own);
        }
        
        return $this;
    }
    
    /**
     * @param     $filename
     * @param int $permission
     * @param int $own
     * @return Image
     */
    public function saveGif($filename, $permission = 0777, $own = -1)
    {
        return $this->save($filename, IMAGETYPE_GIF, -1, $permission, $own);
    }
    
    /**
     * @param     $filename
     * @param int $permission
     * @param int $own
     * @return Image
     */
    public function savePng($filename, $permission = 0777, $own = -1)
    {
        return $this->save($filename, IMAGETYPE_PNG, -1, $permission, $own);
    }
    
    /**
     * @param     $filename
     * @param int $quality
     * @param int $permission
     * @param int $own
     * @return Image
     */
    public function saveJpeg($filename, $quality = 50, $permission = 0777, $own = -1)
    {
        return $this->save($filename, IMAGETYPE_JPEG, $quality, $permission, $own);
    }
    
    /**
     * @param int $width
     * @param int $height
     * @return $this
     */
    public function resize($width = -1, $height = -1)
    {
        if (0 >= (int)$width || 0 >= (int)$height) {
            throw new \InvalidArgumentException(sprintf('Invalid width or height [%sx%s]', $width, $height));
        }
        
        $blankImage = imagecreatetruecolor($width, $height);
        imagecopyresampled($blankImage, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $blankImage;
        
        return $this;
    }
    
    /**
     * @param $width
     * @return $this
     */
    public function resizeToWidth($width)
    {
        $ratio = $width / $this->getWidth();
        $height = $this->getHeight() * $ratio;
        
        return $this->resize($width, $height);
    }
    
    /**
     * @param $height
     * @return $this
     */
    public function resizeToHeight($height)
    {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        
        return $this->resize($width, $height);
    }
    
    /**
     * @param int $scale
     * @return $this
     */
    public function scale($scale = 50)
    {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getHeight() * $scale / 100;
        
        return $this->resize($width, $height);
    }
    
    /**
     * @param int $height
     * @return $this
     */
    public function cropHeight($height = -1)
    {
        return $this->crop($this->getWidth(), $height);
    }
    
    /**
     * @param int $width
     * @return $this
     */
    public function cropWidth($width = -1)
    {
        return $this->crop($width, $this->getHeight());
    }
    
    /**
     * @param int $width
     * @param int $height
     * @return $this
     */
    public function crop($width = -1, $height = -1)
    {
        if (0 >= (int)$width || 0 >= (int)$height) {
            throw new \InvalidArgumentException(sprintf('Invalid width or height [%sx%s]', $width, $height));
        }
        
        $blankImage = imagecreatetruecolor($width, $height);
        imagefill($blankImage, 0, 0, 0xFFFFFF);
        
        imagecopy($blankImage, $this->image, 0, 0, 0, 0, $width, $height);
        
        $this->image = $blankImage;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getWidth()
    {
        return imagesx($this->image);
    }
    
    /**
     * @return int
     */
    public function getHeight()
    {
        return imagesy($this->image);
    }
    
    /**
     * @param int $position
     * @return $this
     */
    public function addWatermark($position = self::WATERMARK_RB)
    {
        $margin = 10;
        $imageWidth = $this->getWidth();
        $imageHeight = $this->getHeight();
        $watermarkWidth = imagesx($this->watermark);
        $watermarkHeight = imagesy($this->watermark);
        
        switch ($position) {
            
            case self::WATERMARK_LT:
                $x = $margin;
                $y = $margin;
                
                imagecopy($this->image, $this->watermark, $x, $y, 0, 0, $watermarkWidth, $watermarkHeight);
                break;
            
            case self::WATERMARK_RT:
                $x = $imageWidth - $margin - $watermarkWidth;
                $y = $margin;
                
                imagecopy($this->image, $this->watermark, $x, $y, 0, 0, $watermarkWidth, $watermarkHeight);
                break;
            
            case self::WATERMARK_LB:
                $x = $margin;
                $y = $imageHeight - $margin - $watermarkHeight;
                
                imagecopy($this->image, $this->watermark, $x, $y, 0, 0, $watermarkWidth, $watermarkHeight);
                break;
            
            case self::WATERMARK_RB:
                $x = $imageWidth - $margin - $watermarkWidth;
                $y = $imageHeight - $margin - $watermarkHeight;
                
                imagecopy($this->image, $this->watermark, $x, $y, 0, 0, $watermarkWidth, $watermarkHeight);
                break;
            
            case self::WATERMARK_MIDDLE:
                $x = $imageWidth - (($imageWidth / 2) + ($watermarkWidth / 2));
                $y = $imageHeight - (($imageHeight / 2) + ($watermarkHeight / 2));
                
                imagecopy($this->image, $this->watermark, $x, $y, 0, 0, $watermarkWidth, $watermarkHeight);
                break;
            
            case self::WATERMARK_TILE:
                $inline = ceil($imageWidth / $watermarkWidth);
                $lines = ceil($imageHeight / $watermarkHeight);
                
                for ($i = 0; $i < $lines; $i++) {
                    for ($j = 0; $j < $inline; $j++) {
                        $x = $j * $watermarkWidth;
                        $y = $i * $watermarkHeight;
                        
                        imagecopy($this->image, $this->watermark, $x, $y, 0, 0, $watermarkWidth, $watermarkHeight);
                    }
                }
                
                break;
        }
        
        return $this;
    }
    
    /**
     * @return $this
     */
    public function destroy()
    {
        @ imagedestroy($this->image);
        @ imagedestroy($this->original);
        @ imagedestroy($this->watermark);
        
        return $this;
    }
    
    /**
     * @return Image
     */
    static public function instance()
    {
        static $instance;
        
        if (empty($instance)) {
            $instance = new Image;
        }
        
        return $instance;
    }
    
}
