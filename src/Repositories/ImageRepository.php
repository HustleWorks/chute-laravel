<?php

namespace HustleWorks\ChuteLaravel\Repositories;

use HustleWorks\Chute\Contracts\ImageRepositoryInterface;
use HustleWorks\Chute\DTO\ImageRecord;
use HustleWorks\ChuteLaravel\Models\Image;

class ImageRepository implements ImageRepositoryInterface
{
    public function create(array $attributes): ImageRecord
    {
        $image                 = new Image($attributes);
        $image->imageable_id   = $attributes['imageable_id'];
        $image->imageable_type = $attributes['imageable_type'];
        $image->save();

        return $this->_buildImageRecordFromImage($image);
    }

    /**
     * Find By Id
     *
     * @param $id
     * @return ImageRecord
     */
    public function findById($id): ImageRecord
    {
        return $this->_buildImageRecordFromImage(Image::find($id));
    }

    /**
     * Update
     *
     * @param int|ImageRecord $model
     * @param array           $attributes
     * @return mixed
     */
    public function update($model, array $attributes)
    {
        $image = $this->_findRecord($model);
        $image->update($attributes);

        return $this->_buildImageRecordFromImage($image, true);
    }

    /**
     * @param $identifier
     * @return Image
     */
    private function _findRecord($identifier)
    {
        /** @var Image $image */
        if ($identifier instanceof ImageRecord) {
            $image = Image::findOrFail($identifier->id);
        } else {
            $image = Image::findOrFail($identifier);
        }

        return $image;
    }

    /**
     * Build Image Record From Image
     *
     * @param Image $image
     * @param bool  $refresh
     * @return ImageRecord
     */
    private function _buildImageRecordFromImage(Image $image, bool $refresh = false)
    {
        $data = $refresh ? $image->fresh()->getAttributes() : $image->getAttributes();
        unset($data['imageable_id'], $data['imageable_type']);

        return new ImageRecord($data);
    }

    /**
     * Find Existing Relation
     *
     * Search for existing image, by the existing polymorphic identifiers
     *
     * @param array $identifiers for polymorphic relationship
     * @return ImageRecord
     */
    public function findExistingImageable(array $identifiers)
    {
        $image = Image::where('imageable_id', $identifiers['imageable_id'])
            ->where('imageable_type', $identifiers['imageable_type'])
            ->first();

        return $image_record = $image ? $this->_buildImageRecordFromImage($image) : null;
    }

    /**
     * Delete
     *
     * @param int|ImageRecord $model
     * @return bool
     * @throws \Exception
     */
    public function delete($model)
    {
        $image = $this->_findRecord($model);

        return $image->delete();
    }
}