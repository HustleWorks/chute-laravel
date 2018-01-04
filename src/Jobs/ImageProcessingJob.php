<?php

namespace HustleWorks\ChuteLaravel\Jobs;

use HustleWorks\Chute\DTO\ImageConfiguration;
use HustleWorks\ChuteLaravel\Services\ImageProcessorService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImageProcessingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    private $data;

    /**
     * @var ImageConfiguration
     */
    private $config;

    /**
     * Create a new job instance.
     *
     * @param array              $data
     * @param ImageConfiguration $config
     */
    public function __construct(array $data, ImageConfiguration $config)
    {
        $this->data   = $data;
        $this->config = $config;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new ImageProcessorService)->handle($this->data['image'], $this->config);
    }
}
