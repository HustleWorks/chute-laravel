<?php

namespace HustleWorks\ChuteLaravel\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ImageTransformation
 *
 * @property int                                  $id
 * @property int                                  $image_original_id
 * @property string                               $filename
 * @property string                               $name
 * @property string                               $disk
 * @property string                               $path
 * @property string|null                          $width
 * @property string|null                          $height
 * @property string|null                          $quality
 * @property int|null                             $size_in_kilobytes
 * @property string|null                          $mime_type
 * @property \Carbon\Carbon|null                  $created_at
 * @property \Carbon\Carbon|null                  $updated_at
 * @property-read \HustleWorks\ChuteLaravel\Models\Image $imageOriginal
 * @method static Builder|ImageTransformation whereCreatedAt($value)
 * @method static Builder|ImageTransformation whereDisk($value)
 * @method static Builder|ImageTransformation whereHeight($value)
 * @method static Builder|ImageTransformation whereId($value)
 * @method static Builder|ImageTransformation whereImageOriginalId($value)
 * @method static Builder|ImageTransformation whereMimeType($value)
 * @method static Builder|ImageTransformation whereName($value)
 * @method static Builder|ImageTransformation wherePath($value)
 * @method static Builder|ImageTransformation whereQuality($value)
 * @method static Builder|ImageTransformation whereSizeInKilobytes($value)
 * @method static Builder|ImageTransformation whereUpdatedAt($value)
 * @method static Builder|ImageTransformation whereWidth($value)
 * @mixin \Eloquent
 */
class ImageTransformation extends Model
{
    protected $fillable = [
        'image_original_id',
        'disk',
        'filename',
        'name',
        'path',
        'width',
        'height',
        'quality',
        'size',
        'mime_type',
        'size_in_kilobytes',
    ];

    /**
     * Image Original
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    protected function imageOriginal()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }

    public function imageUrl()
    {
        return \Storage::disk($this->imageOriginal->disk)
            ->url("{$this->imageOriginal->path}/{$this->imageOriginal->uuid}/$this->filename");
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (ImageTransformation $image_transformation) {
            $image_original = $image_transformation->imageOriginal;
            $path           = "$image_original->directory/$image_original->uuid/$image_transformation->path";
            if (\Storage::disk($image_transformation->disk)->exists($path)) {
                \Storage::disk($image_transformation->disk)->delete($path);
            }
            if (!\Storage::disk($image_transformation->disk)->files("$image_original->directory/$image_original->uuid")) {
                \Storage::disk($image_transformation->disk)->deleteDirectory("$image_original->directory/$image_original->uuid");
            }
        });
    }
}
