<?php

namespace HustleWorks\ChuteLaravel\Traits;

use HustleWorks\Chute\DTO\ImageConfiguration;
use HustleWorks\ChuteLaravel\Jobs\ImageProcessingJob;
use HustleWorks\ChuteLaravel\Models\Image;
use HustleWorks\Chute\ImageConfigurationBuilder;
use HustleWorks\ChuteLaravel\Services\ImageUploadService;

/**
 * Trait ChuteImageModelTrait
 *
 * @property string[] $chute_image_sizes
 * @property array    $chute_image_rule
 * @property int      $id
 * @mixin \Eloquent
 */
trait ChuteImageModelTrait
{
    /**
     * Upload Image
     *
     * Perform the image upload process
     *
     * @param $file
     * @param $name
     * @return \HustleWorks\Chute\ServiceResponse
     */
    public function uploadImage($file, $name)
    {
        /* upload file */
        $response = (new ImageUploadService)->handle($file, $this->imageUploadConfig($name));

        /* queue processing */
        dispatch(new ImageProcessingJob($response->data(), $this->imageUploadConfig($name)));

        return $response;
    }

    /**
     * Image URl
     *
     * @param      $name
     * @param null $size
     * @return \HustleWorks\ChuteLaravel\Models\ImageTransformation|string
     */
    public function imageUrl($name, $size = null)
    {
        /** @var Image $image */
        $image = $this->images()->where('name', $name)->first();

        return $size ? $image->getTransformationUrl($size) : $image->getOriginalUrl();
    }

    /**
     * Image Upload Configuration
     *
     * @param null $image_name
     * @return ImageConfiguration
     */
    public function imageUploadConfig($image_name = null)
    {
        return (new ImageConfigurationBuilder())->getConfig(
            $image_name,
            $this->chute_image_rule[ $image_name ],
            $this->chute_image_sizes[ $image_name ],
            [
                'imageable_type' => $this->getMorphClass(),
                'imageable_id'   => $this->id,
            ]
        );
    }

    /**
     * Images
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    private function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

}