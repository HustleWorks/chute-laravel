<?php


namespace HustleWorks\ChuteLaravel\Repositories;


use HustleWorks\Chute\Contracts\ImageTransformationRepositoryInterface;
use HustleWorks\Chute\DTO\ImageTransformationRecord;
use HustleWorks\Chute\DTO\ImageRecord;
use HustleWorks\ChuteLaravel\Models\Image;
use HustleWorks\ChuteLaravel\Models\ImageTransformation;

class ImageTransformationRepository implements ImageTransformationRepositoryInterface
{

    /**
     * Create
     *
     * @param array       $attributes
     * @param ImageRecord $image_record that this belongs to
     * @return ImageTransformationRecord
     */
    public function create(array $attributes, ImageRecord $image_record): ImageTransformationRecord
    {
        /** @var Image $image */
        $image = Image::findOrFail($image_record->id);
        $image->imageTransformations()->save($transformation = new ImageTransformation($attributes));

        return new ImageTransformationRecord($transformation->getAttributes());
    }

    /**
     * Find By Id
     *
     * @param $id
     * @return ImageTransformationRecord
     */
    public function findById($id): ImageTransformationRecord
    {
        return new ImageTransformationRecord(ImageTransformation::findOrFail($id)->getAttributes());
    }
}