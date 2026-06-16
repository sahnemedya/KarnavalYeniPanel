<?php

namespace App\Services;

use App\Models\Language;
use App\Models\Podcasts;
use Illuminate\Http\Request;

class PodcastServices
{
    protected CommonService $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index()
    {
        $langId = request('lang_id', 1);

        $podcasts = Podcasts::where('lang_id', $langId)
            ->orderBy('id', 'desc')
            ->get();

        return [
            'podcasts'  => $podcasts,
            'languages' => Language::all()
        ];
    }

    public function store(Request $request)
    {
        $status = "success";

        $count = is_array($request->name)
            ? count($request->name)
            : 0;

        $message = "$count Adet Podcast Kaydedildi";

        try {

            if ($request->has('name') && is_array($request->name)) {

                foreach ($request->name as $key => $name) {

                    // Boş satırları atla
                    if (empty($name)) {
                        continue;
                    }

                    Podcasts::create([
                        'name'      => $name,
                        'link'      => $request->link[$key] ?? null,
                        'page_id'   => $request->page_id,
                        'lang_id'   => $request->lang_id
                    ]);
                }
            }

            LogService::add(
                "Podcast Store",
                $status,
                $message
            );

            return [
                "status" => $status,
                "message" => $message
            ];

        } catch (\Throwable $exception) {

            $status = "error";
            $message = "Podcastlar Kaydedilemedi";

            LogService::add(
                "Podcast Store",
                $status,
                $message . " => " . $exception->getMessage()
            );

            return [
                "status" => $status,
                "message" => $message
            ];
        }
    }

    public function destroy($id)
    {
        $status = "success";
        $message = "Podcast Silindi";

        try {

            $podcast = Podcasts::findOrFail($id);

            $podcastName = $podcast->name;

            $podcast->delete();

            LogService::add(
                "Podcast Destroy",
                $status,
                $podcastName . " " . $message
            );

            return [
                "status" => $status,
                "message" => $message
            ];

        } catch (\Throwable $exception) {

            $status = "error";
            $message = "Podcast Silinemedi";

            LogService::add(
                "Podcast Destroy",
                $status,
                $message . " => " . $exception->getMessage()
            );

            return [
                "status" => $status,
                "message" => $message
            ];
        }
    }
}
