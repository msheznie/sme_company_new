<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DeleteFileFromS3Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $path;
    public $disk;

    /**
     * Create a new job instance.
     *
     * @param string $path
     * @param string $disk
     * @return void
     */
    public function __construct($path, $disk)
    {
        $this->path = $path;
        $this->disk = $disk;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Delete the file from the disk
            if ($exists = Storage::disk($this->disk)->exists($this->path)) {
                Storage::disk($this->disk)->delete($this->path);
            }
        } catch (\Exception $e) {
            Log::error('Error deleting file from the disk: ' . $e->getMessage());
        }
    }
}
