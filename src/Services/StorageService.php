<?php

namespace HustleWorks\ChuteLaravel\Services;

use HustleWorks\Chute\Contracts\StorageInterface;
use Storage;

/**
 * Storage Service
 *
 * @category Services
 * @package  App\Services
 * @author   Don Herre <don@hustleworks.com>
 * @license  Proprietary and confidential
 * @link     http://patronart.com
 */
class StorageService implements StorageInterface
{
    /**
     * Put
     *
     * @param string      $file
     * @param string      $disk
     * @param string      $path
     * @param string|null $filename
     * @return mixed
     */
    public function put($file, $disk, $path, $filename = null)
    {
        $path = $filename ? "$path/$filename" : $path;
        Storage::disk($disk)->put($path, $file);

        return $this->sizeOnDisk($disk, $path);
    }

    /**
     * Size On Disk
     *
     * @param $disk
     * @param $path
     * @return bool|int
     */
    public function sizeOnDisk($disk, $path)
    {
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->size($path);
        }

        return false;
    }

    /**
     * Get
     *
     * @param string $disk
     * @param string $path
     * @return false|string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get($disk, $path)
    {
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->get($path);
        }

        return false;
    }

    /**
     * Move
     *
     * @param string      $source_path
     * @param string      $destination_path
     * @param string|null $source_disk
     * @param string|null $destination_disk
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function move($source_path, $destination_path, $source_disk = null, $destination_disk = null)
    {
        if (!($source_disk and $destination_disk)) {
            return Storage::disk($source_disk)->move($source_path, $destination_path);
        }

        if (Storage::disk($source_disk)->exists($source_path)) {
            $file_stream = $this->get($source_disk, $source_path);
            if ($this->put($file_stream, $destination_disk, $destination_path)) {
                return $this->delete($source_disk, $source_path);
            }
        }

        return false;
    }

    public function delete($disk, $path)
    {
        return Storage::disk($disk)->delete($path);
    }
}