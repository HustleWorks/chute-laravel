<?php


namespace HustleWorks\ChuteLaravel\Services;


use Exception;
use HustleWorks\Chute\ImageFile;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;

class UploadedFileImage extends ImageFile
{
    /**
     * @param  UploadedFile $framework_file
     * @return void
     * @throws Exception
     */
    protected function processImageData($framework_file)
    {
        /* make sure we get the object type we're expecting */
        if (!$framework_file instanceof UploadedFile) {
            throw new Exception('You must provide an instance of the UploadedFile class');
        }

        /* set up modules for gathering data */
        $this->stream = file_get_contents($framework_file);
        $image = (new ImageManager())->make($this->stream);

        /* set all remaining data points */
        $this->extension = $framework_file->getClientOriginalExtension();
        $this->size      = $framework_file->getClientSize();
        $this->filename  = $framework_file->getClientOriginalName();
        $this->mime_type = $framework_file->getClientMimeType();
        $this->width     = $image->width();
        $this->height    = $image->height();
    }
}