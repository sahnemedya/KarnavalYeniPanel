<?php

namespace App\Services;



use App\Models\Comments;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CommentService
{
    protected CommonService $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function store(Request $request)
    {
        $status = "success";
        $message = "Karnaval Yorumcuları Kaydedildi";

        try {

             $comments= Comments::create([
                'name' => $request->name,
                'content' => $request->content_text,
                'hit' => $request->hit,
                'lang_id' => $request->lang_id,
            ]);

            LogService::add("Comments Service Store", $status, $message);
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Karnaval Yorumcu Kaydedilemedi";
            LogService::add("Comments Service Store", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function destroy($id)
    {
        $status = "success";
        $message = "Karnaval Yorumcu Gecici Olarak Silindi";
        try {
            $comments = Comments::findOrFail($id);
            $comments->delete();
            LogService::add("Comments Service Destroy", $status, $comments->name . " " . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Karnaval Yorumcu Gecici Olarak Silinemedi";
            LogService::add("Comments Service Destroy", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }
    public function restore($id)
    {
        $status = "success";
        $message = 'Karnaval Yorumcu Geri Yüklendi';
        try {
            $comments = Comments::onlyTrashed()->findOrFail($id);
            $comments->restore();
            $message = $comments->name . ' ' . $message;
            LogService::add("Comments Service Restore", $status, $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Karnaval Yorumcu Geri Yüklenemedi";
            LogService::add("Comments Service Restore ", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }

    }

    public function forceDelete($id)
    {
        $status = "success";
        $message = 'Karnaval Yorumcu Kalıcı Olarak Silindi';

        try {
            $comments = Comments::onlyTrashed()->findOrFail($id);
            $comments->forceDelete();
            $message = $comments->name . ' Karnaval Yorumcu Kalıcı Olarak Silindi';

            LogService::add("Comments Service ForceDelete", $status, $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Karnaval Yorumcu Kalıcı Olarak Silinemedi";
            LogService::add("Comments Service ForceDelete ", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }

    }
    public function update(Request $request, $id)
    {
        $status = "success";
        $message = "Karnaval Yorumcu Başarıyla Güncellendi";

        try {
            $comments = Comments::findOrFail($id);
            // ======================================================
            // 1. IMAGE İŞLEMLERİ (Sayfa Resmi)
            // ======================================================
            if ($request->has('remove_image')) {
                if ($comments->image) {
                    $this->commonService->deleteFile(config('constants.comments_path'), $comments->image);
                }
                $comments->update(["image" => NULL]);

            }

            if ($request->hasFile('image')) {
                if ($comments->image) {
                    $this->commonService->deleteFile(config('constants.comments_path'), $comments->image);
                }
                $imageFile = $request->file('image');
                $extension = $imageFile->guessExtension();

                $fileName = Str::slug($request->name) . '-' . Str::lower(Str::random(4)) . '.' . $extension;
                $this->commonService->uploadFile(config('constants.comments_path'), $imageFile, $fileName);
                $comments->update(["image" => $fileName]);
            }
            $comments->update([
                'name' => $request->name,
                'content' => $request->content_text,
                'hit' => $request->hit,
                'lang_id' => $request->lang_id,
            ]);

            LogService::add("Comments Service Update", $status, $message);
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Karnaval Yorumcu Güncellenemedi";
            LogService::add("Comments Service Update ", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }
    public function publish($id)
    {
        $status = "success";
        $message = "Karnaval Yorumcu Yayınlandı";
        try {
            $comments = Comments::findOrFail($id);
            if ($comments->published == 1) {
                $comments->update(["published" => 0]);
                $message = "Karnaval Yorumcu Kaldırıldı.";
            } else {
                $comments->update(["published" => 1]);
                $message = "Karnaval Yorumcu Yayınlandı";
            }
            LogService::add("Comments Service Publish", $status, $comments->name . " " . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Karnaval Yorumcu  İşlemi Başarısız";
            LogService::add("Comments Service Publish", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }
}
