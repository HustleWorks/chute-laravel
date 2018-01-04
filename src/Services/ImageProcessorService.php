<?php


namespace HustleWorks\ChuteLaravel\Services;


use HustleWorks\Chute\ImageProcessor;
use HustleWorks\ChuteLaravel\Repositories\ImageRepository;
use HustleWorks\ChuteLaravel\Repositories\ImageTransformationRepository;

class ImageProcessorService extends ImageProcessor
{

    /**
     * ImageProcessorService constructor.
     */
    public function __construct() {
        parent::__construct(new StorageService, new ImageRepository, new ImageTransformationRepository);
    }
}