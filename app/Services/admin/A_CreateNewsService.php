<?php

namespace App\Services\admin;

use App\Notifications\SendNewsNotification;
use App\Repositories\Interfaces\NewsInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

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
    public function create(array $data)
    {
        $createdNew = $this->news->create($data);

        if(request()->has('files')) {
            $dir = storage_path('app/public') . '/images/news/';
            foreach (Collection::wrap(request()->file('files')) as $file) {
                $fileName = hexdec(crc32($createdNew->id)).'.' . $file->getClientOriginalExtension();
                $uploadedFile = $file->move($dir, $fileName);
                if($uploadedFile) {
                    $createdNew->update([
                        'image' => 'images/news/' .$fileName,
                    ]);
                }
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
