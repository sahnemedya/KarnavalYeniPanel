<?php

namespace App\Services;

use App\Models\Blade;
use App\Models\Contacts;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SocialMediaService
{
    protected CommonService $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
        //$this->commonService-> olarak kullanılacak
    }


    public function create($contactId)
    {
        try {
            $contact = Contacts::find($contactId);
            return ["status" => "success", "message" => "İletişim Bilgisi Bulundu."];
        } catch (\Throwable $exception) {
            LogService::add("Social Media Service Create", "error", "İletişim Bilgisi Bulunamadı: => " . $exception->getMessage());
            return ["status" => "error", "message" => "İletişim Bilgisi Bulunamadı."];
        }
    }

    public function store(Request $request, $contactId)
    {
        $status = 'success';
        $message = 'Sosyal Medya Bilgisi Kaydedildi';
        try {
            $contact = Contacts::select("id")->where("id", $contactId)->first("id");
            $social = SocialMedia::create([
                'contacts_id' => $contact->id,
                'facebook' => $request->facebook,
                'instagram' => $request->instagram,
                'twitter' => $request->twitter,
                'linkedin' => $request->linkedin,
                'youtube' => $request->youtube,
                'tiktok' => $request->tiktok,
                'whatsapp' => $request->whatsapp,
                'telegram' => $request->telegram,
                'behance' => $request->behance,
                'pinterest' => $request->pinterest,
                'threads' => $request->threads,
                'reddit' => $request->reddit
            ]);
            LogService::add("Social Media Service Store", $status, $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = "Sosyal Medya Bilgisi Kaydedilemedi";
            LogService::add("Social Media Service Store", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }


    public function edit($id)
    {
        try {
            $socialMedia = SocialMedia::findOrFail($id);
            return [
                "status" => "success",
                "message" => "Sosyal Medya Bilgileri Bulundu.",
                "socialMedia" => $socialMedia];
        } catch (\Throwable $exception) {
            LogService::add("Social Media Service Edit", "error", "Sosyal Medya Bilgileri Bulunamadı: => " . $exception->getMessage());
            return ["status" => "error", "message" => "İletişim Bilgisi Bulunamadı."];
        }
    }

    public function update(Request $request,$id)
    {
        $status = 'success';
        $message = 'Sosyal Medya Bilgisi Güncellendi';

        try {
            $social = SocialMedia::findOrFail($id);
            $social->update([
                'facebook' => $request->facebook,
                'instagram' => $request->instagram,
                'twitter' => $request->twitter,
                'linkedin' => $request->linkedin,
                'youtube' => $request->youtube,
                'tiktok' => $request->tiktok,
                'whatsapp' => $request->whatsapp,
                'telegram' => $request->telegram,
                'behance' => $request->behance,
                'pinterest' => $request->pinterest,
                'threads' => $request->threads,
                'reddit' => $request->reddit
            ]);
            LogService::add("Social Media Service Update", $status, $social->contact->name . ' ' . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = "Sosyal Medya Bilgisi Güncellenemedi";
            LogService::add("Social Media Service Update", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function destroy($id)
    {
        $status = 'success';
        $message = 'Sosyal Medya Bilgisi Silindi';

        try {
            $social = SocialMedia::findOrFail($id);
            $social->delete();
            LogService::add("Social Media Service Destroy", $status, $social->contact->name . ' ' . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'Sosyal Medya Bilgisi Silinemedi';
            LogService::add('Social Media Service Destroy', $status, $message . " => " . $exception->getMessage());
            return ['status' => $status, 'message' => $message];
        }

    }

}
