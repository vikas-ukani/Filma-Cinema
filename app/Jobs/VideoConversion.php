<?php

namespace App\Jobs;
use App\Videolink;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class VideoConversion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

     protected $videoname;

    public function __construct(Videolink $videolink)
    {
        $this->videoname = $videolink->upload_video;
    }

    public function handle()
    {
      
   \FFMpeg::fromDisk('movies_upload')
   ->open($this->videoname)
   ->addFilter(function ($filters) {
    $filters->resize(new \FFMpeg\Coordinate\Dimension(640, 360));
  })
     -> addFilter('-preset', 'ultrafast')
   ->export()  
   ->toDisk('movie_360')
   ->inFormat(new \FFMpeg\Format\Video\X264('libmp3lame', 'libx264'))
   ->save('360_'.$this->videoname);
   
    \FFMpeg::fromDisk('movies_upload')
   ->open($this->videoname)
   ->addFilter(function ($filters) {
    $filters->resize(new \FFMpeg\Coordinate\Dimension(2000, 1080));
  })
   ->export()  
   ->toDisk('movie_1080')
   ->inFormat(new \FFMpeg\Format\Video\X264('libmp3lame', 'libx264'))
   ->save('1080_'.$this->videoname); 
   shell_exec('php artisan queue:listen --timeout=0');
    // \Artisan::call('queue:listen --timeout=0');
    }
}
