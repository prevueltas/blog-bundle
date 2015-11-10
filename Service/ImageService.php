<?php

namespace Prh\BlogBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile as File;
use Eventviva\ImageResize;

/**
 * Class ImageService.
 */
class ImageService
{
    /**
     * @var int
     */
    public $sizeSmall;

    /**
     * @var int
     */
    public $sizeMedium;

    /**
     * @var int
     */
    public $sizeBig;

    /**
     * @var int
     */
    public $sizeMax;

    /**
     * Constructor.
     *
     * @param array $config Bundle configuration
     */
    public function __construct(array $config)
    {
        $this->sizeSmall = $config['image_size_small'];
        $this->sizeMedium = $config['image_size_medium'];
        $this->sizeBig = $config['image_size_big'];
        $this->sizeMax = $config['image_size_max'];
    }

    /**
     * Resize an image and optionally rename it.
     *
     * @param string $path
     * @param string $filename
     * @param int $width
     * @param string|null $newFilename
     */
    public function resizeToWidth($path, $filename, $width, $newFilename = null)
    {
        $fullFilename = $path . '/' . $filename;

        $image = new ImageResize($fullFilename);
        $image->resizeToWidth($width, true);

        if (is_string($newFilename) && !empty($newFilename)) {
            $image->save($path . '/' . $newFilename);

            return;
        }

        $image->save($fullFilename);
    }

    /**
     * Create a new filename containing a size extension.
     *
     * @param string $filename
     * @param string $sizeExtension
     * @return string
     */
    public function appendSizeExt($filename, $sizeExtension)
    {
        $parts = pathinfo($filename);

        return $parts['filename'] . '_' . $sizeExtension . '.' . $parts['extension'];
    }

    /**
     * Create resized images.
     *
     * @param string $path
     * @param File $file
     */
    public function createResizedImages($path, File $file)
    {
        $filename = $file->getClientOriginalName();
        $file->move($path, $filename);

        $this->resizeToWidth($path, $filename, $this->sizeMax);
        $this->resizeToWidth($path, $filename, $this->sizeSmall, $this->appendSizeExt($filename, 's'));
        $this->resizeToWidth($path, $filename, $this->sizeMedium, $this->appendSizeExt($filename, 'm'));
        $this->resizeToWidth($path, $filename, $this->sizeBig, $this->appendSizeExt($filename, 'b'));
    }
}
