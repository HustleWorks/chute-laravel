<?php

namespace HustleWorks\ChuteLaravel\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * \HustleWorks\ChuteLaravel\Models\Image
 *
 * @property int                                                                                           $id
 * @property int|null                                                                                      $imageable_id
 * @property string|null                                                                                   $imageable_type
 * @property string                                                                                        $name
 * @property string                                                                                        $filename
 * @property string                                                                                        $disk
 * @property string                                                                                        $directory
 * @property string                                                                                        $uuid
 * @property string                                                                                        $path
 * @property string                                                                                        $status
 * @property string|null                                                                                   $width
 * @property string|null                                                                                   $height
 * @property int|null                                                                                      $size_in_kilobytes
 * @property string|null                                                                                   $mime_type
 * @property string|null                                                                                   $caption
 * @property \Carbon\Carbon|null                                                                           $created_at
 * @property \Carbon\Carbon|null                                                                           $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\HustleWorks\ChuteLaravel\Models\ImageTransformation[] $imageTransformations
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent                                            $imageable
 * @method static Builder|Image whereCaption($value)
 * @method static Builder|Image whereCreatedAt($value)
 * @method static Builder|Image whereDirectory($value)
 * @method static Builder|Image whereDisk($value)
 * @method static Builder|Image whereHeight($value)
 * @method static Builder|Image whereId($value)
 * @method static Builder|Image whereImageableId($value)
 * @method static Builder|Image whereImageableType($value)
 * @method static Builder|Image whereMimeType($value)
 * @method static Builder|Image whereName($value)
 * @method static Builder|Image wherePath($value)
 * @method static Builder|Image whereSizeInKilobytes($value)
 * @method static Builder|Image whereStatus($value)
 * @method static Builder|Image whereUpdatedAt($value)
 * @method static Builder|Image whereUuid($value)
 * @method static Builder|Image whereWidth($value)
 * @mixin \Eloquent
 */
class Image extends Model
{
    protected $fillable = [
        'disk',
        'name',
        'filename',
        'path',
        'size',
        'status',
        'width',
        'height',
        'mime_type',
        'extension',
        'alt',
        'title',
        'description',
    ];

    public function getTransformationUrl($name)
    {
        /** @var ImageTransformation $transformation */
        $transformation = $this->imageTransformations()->where('name', $name)->first();

        return $transformation->imageUrl();
    }

    public function getOriginalUrl()
    {
        return \Storage::disk($this->disk)->url("$this->path/$this->uuid/$this->filename");
    }

    /**
     * Image Transformations
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function imageTransformations()
    {
        return $this->hasMany(ImageTransformation::class);
    }

    /**
     * Imageable
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function imageable()
    {
        return $this->morphTo();
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Image $model) {
            // Don't let people provide their own UUIDs, we will generate a proper one.
            $model->uuid = Uuid::uuid4()->toString();
        });

        static::saving(function (Image $model) {
            // What's that, trying to change the UUID huh?  Nope, not gonna happen.
            $original_uuid = $model->getOriginal('uuid');

            if ($original_uuid !== $model->uuid) {
                $model->uuid = $original_uuid;
            }
        });

        static::deleting(function (Image $image_original) {

            /* if the original is deleted all transformations must be deleted */
            foreach ($image_original->imageTransformations as $transformation) {
                $transformation->delete();
            }

            /* get the path */
            $path = "$image_original->directory/$image_original->uuid/$image_original->path";

            /* delete the saved file */
            if (\Storage::disk($image_original->disk)->exists($path)) {
                \Storage::disk($image_original->disk)->delete($path);
            }

            /* delete the directory */
            if (!\Storage::disk($image_original->disk)->files("$image_original->directory/$image_original->uuid")) {
                \Storage::disk($image_original->disk)->deleteDirectory("$image_original->directory/$image_original->uuid");
            }
        });
    }
}
