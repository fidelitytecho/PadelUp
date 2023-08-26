<?php

namespace App\Services\auth;

use App\Http\Resources\UserResource;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class UpdateProfileService
{
    private $user;

    /**
     * Create a new instance.
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Login User With Mobile
     * @param array $data
     * @param int $id
     * @return UserResource
     */
    public function updateProfile(array $data, int $id, $image = null): UserResource
    {
        $updatedUser = $this->user->update($data, $id);
        if($image) {
            // $dir = storage_path('app/public') . '/images/news/';
            $fileName = hexdec(crc32($updatedUser->id)).'.' . $image->getClientOriginalExtension();
                // $fileName = $image->getClientOriginalName();
                // $uploadedFile = $file->move($dir, $fileName);
            $pathName = 'image/users/';
            $uploadedFile = Storage::disk('local')->put($pathName, $image);
            if($uploadedFile) {
                $updatedUser->update([
                    'image' => $pathName.''.$fileName,
                ]);
                
            }
        }
        return new UserResource($updatedUser->load('Customer.Wallet', 'Skill'));
    }
}
