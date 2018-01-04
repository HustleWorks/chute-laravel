<?php


namespace HustleWorks\ChuteLaravel\Services;

use HustleWorks\Chute\ImageFile;
use HustleWorks\Chute\ImageUploader;
use HustleWorks\Chute\ImageValidator;
use HustleWorks\ChuteLaravel\Repositories\ImageRepository;

class ImageUploadService extends ImageUploader
{
    /**
     * ImageUploadService constructor.
     */
    public function __construct()
    {
        parent::__construct(new ImageValidator, new StorageService, new ImageRepository);
    }

    /**
     * Prepare Image File
     *
     * Take raw upload and convert it to an ImageFile object
     *
     * @param $framework_file
     * @return ImageFile
     */
    protected function prepareImageFile($framework_file): ImageFile
    {
        return new UploadedFileImage($framework_file);
    }
}