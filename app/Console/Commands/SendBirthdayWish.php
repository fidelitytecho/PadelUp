<?php

namespace App\Console\Commands;

use App\Models\Promo;
use App\Models\User;
use App\Notifications\sendPromo;
use App\Notifications\sendWish;
use App\Repositories\Interfaces\PromoInterface;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Str;
use Twilio\Rest\Client;


class SendBirthdayWish extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-birthday-wish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send birthday Wishes and Promo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $wish = User::where('birthday', Carbon::now()->format('d-m-Y'));
        $promo = User::where('birthday', Carbon::now()->subWeek()->format('d-m-Y'));

        if (count($wish) > 0)
        {
            foreach ($wish as $user)
            {
                if ($user->email)
                {
                    Notification::send($user->email, new sendWish('Happy birthday', $user->full_name. ' We are wishing you a happy birthday from '. config('app.name')));
                }elseif ($user->full_mobile) {
                    Notification::send($user->full_mobile, new sendWish('Happy birthday', $user->full_name. ' We are wishing you a happy birthday from '. config('app.name')));
                }
            }
        }
        if (count($promo) > 0) {
            foreach ($promo as $user) 
            {
                if ($user->email)
                {
                    
                    Notification::send($user->email, new sendPromo('Birthday Promo.', $user->full_name .'. We at'. config('app.name'). 'offer a 10% discount for your upcoming birthday. Enter this code to use ', $user->id));
                }elseif ($user->full_mobile) {
                    Notification::send($user->full_mobile, new sendPromo('Birthday Promo.', $user->full_name .'. We at'. config('app.name'). 'offer a 10% discount for your upcoming birthday. Enter this code to use ', $user->id));
                }
            }
        }
    }
}
