<?php

namespace App\Services;

use App\Models\Award;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AwardService
{
    protected CommonService $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index()
    {
        return Award::orderBy('hit', 'asc')->orderBy('prize_date', 'desc')->get();
    }

    public function store(Request $request)
    {
        $status = "success";
        $message = "Ödül Kaydedildi";

        try {
            $slug = Str::slug($request->name, "-") . "-" . Str::lower(Str::random(5));
            $image = null;

            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $extension = $imageFile->guessExtension();
                $image = $slug . "." . $extension;
                $this->commonService->uploadFile(config('constants.awards_path'), $imageFile, $image);
            }

            $award = Award::create([
                'name' => $request->name,
                'image' => $image,
                'prize_date' => $request->prize_date,
                'hit' => $request->hit,
                'published' => $request->has('published') ? 1 : 0,
            ]);

            LogService::add("Award Service Store", $status, $award->name . " " . $message);
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Ödül Kaydedilemedi";
            LogService::add("Award Service Store", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function update(Request $request, $id)
    {
        $status = "success";
        $message = "Ödül Güncellendi";

        try {
            $award = Award::findOrFail($id);

            if ($request->has('remove_image')) {
                if ($award->image) {
                    $this->commonService->deleteFile(config('constants.awards_path'), $award->image);
                }
                $award->update(["image" => null]);
            }

            if ($request->hasFile('image')) {
                if ($award->image) {
                    $this->commonService->deleteFile(config('constants.awards_path'), $award->image);
                }
                $imageFile = $request->file('image');
                $extension = $imageFile->guessExtension();
                $fileName = Str::slug($request->name, "-") . '-' . Str::lower(Str::random(4)) . '.' . $extension;
                $this->commonService->uploadFile(config('constants.awards_path'), $imageFile, $fileName);
                $award->update(["image" => $fileName]);
            }

            $award->update([
                'name' => $request->name,
                'prize_date' => $request->prize_date,
                'hit' => $request->hit,
                'published' => $request->has('published') ? 1 : 0,
            ]);

            LogService::add("Award Service Update", $status, $award->name . " " . $message);
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Ödül Güncellenemedi";
            LogService::add("Award Service Update", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function destroy($id)
    {
        $status = "success";
        $message = "Ödül Silindi";
        try {
            $award = Award::findOrFail($id);
            $awardName = $award->name;
            $award->delete();
            LogService::add("Award Service Destroy", $status, $awardName . " " . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Ödül Silinemedi";
            LogService::add("Award Service Destroy", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function publish($id)
    {
        $status = "success";
        $message = "Ödül Yayınlandı";
        try {
            $award = Award::findOrFail($id);
            if ($award->published == 1) {
                $award->update(["published" => 0]);
                $message = "Ödül Yayından Kaldırıldı.";
            } else {
                $award->update(["published" => 1]);
                $message = "Ödül Yayınlandı";
            }
            LogService::add("Award Service Publish", $status, $award->name . " " . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Ödül Yayın İşlemi Başarısız";
            LogService::add("Award Service Publish", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }
}
