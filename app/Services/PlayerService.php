<?php

namespace App\Services;
use App\Http\Resources\app\BookingsResource;
use App\Notifications\PlayerNotification;
use App\Repositories\Interfaces\BookingInterface;
use App\Repositories\Interfaces\PlayerInterface;
use App\Repositories\Interfaces\UserInterface;
use Notification;

class PlayerService
{
    private $user, $booking, $player;

    public function __construct(UserInterface $user, BookingInterface $booking, PlayerInterface $player)
    {
        $this->user = $user;
        $this->booking = $booking;
        $this->player = $player;
    }

    public function addPlayer($slug)
    {
        $user_id = auth('api')->id();
        $booking = $this->booking->findFirstWhere(['slug' => $slug]);
        $this->player->create(['user_id' => $user_id, 'booking_id' => $booking->id]);
        return new BookingsResource($booking->load(['Player']));
    }

    public function searchPlayer($keyword)
    {
        // $user = $this->user->findById($user_id);
        $users = \App\Models\User::where('first_name', 'like', $keyword.'%')->orWhere('last_name', 'like', $keyword.'%')->get();
        $usernames = \App\Models\User::where('username', 'like', $keyword.'%')->get();
        $response = [];
        if ($users)
        {
            foreach ($users as $user)
            {
                array_push($response, ['id' => $user->id, 'name' => $user->fullname]);
            }
        }
        if ($usernames)
        {
            foreach ($usernames as $user)
            {
                array_push($response, ['id' => $user->id, 'name' => '@'.$user->username]);
            }
        }
        return response()->json([$response]);
    }

    public function notifyPlayer($id, $slug)
    {
        $user = $this->user->findByID($id);
        $booking = $this->booking->findFirstWhere(['slug' => $slug]);
        Notification::send($user, PlayerNotification::class);
    }
}
