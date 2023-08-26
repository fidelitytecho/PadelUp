<?php

namespace App\Services\admin;

use App\Notifications\SendNewsNotification;
use App\Repositories\Interfaces\NewsInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class A_CreateNewsService
{
    private $news;

    /**
     * Create a new instance.
     *
     * @param NewsInterface $news
     */
    public function __construct(NewsInterface $news)
    {
        $this->news = $news;
    }

    /**
     * Create New Notification
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data, $image = null)
    {
        $createdNew = $this->news->create($data);

        if($image) {
            // $dir = storage_path('app/public') . '/images/news/';
            $fileName = hexdec(crc32($createdNew->id)).'.' . $image->getClientOriginalExtension();
                // $fileName = $image->getClientOriginalName();
                // $uploadedFile = $file->move($dir, $fileName);
            $pathName = 'image/news/';
            $uploadedFile = Storage::disk('local')->put($pathName, $image);
            if($uploadedFile) {
                $createdNew->update([
                    'image' => $pathName.''.$fileName,
                ]);
                
            }
        }

        Notification::send('',
        (new SendNewsNotification(
            $data['title'],
            $data['description'],
            asset('storage/' . $createdNew->image), 'newsTopic')));
        return $createdNew;
    }
}
