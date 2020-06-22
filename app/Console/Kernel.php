<?php

namespace App\Console;

use App\Notifications\ScheduleNotification;
use App\Post;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $posts = Post::where('status', true)->where('date', date('Y-m-d', time()))->get();
            //dd($posts);
            foreach ($posts as $post) {

                $newPost = $post->publishToPage($post->page->facebook_id, $post->name);
                //dd($newPost);
                if ($newPost) {
                    $post->page->user->notify(new ScheduleNotification($post));
                }
            }
        })->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
