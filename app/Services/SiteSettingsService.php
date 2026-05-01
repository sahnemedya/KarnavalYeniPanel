<?php

namespace App\Services;

use App\Models\Blade;
use App\Models\SiteSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SiteSettingsService
{
    protected CommonService $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
        //$this->commonService-> olarak kullanılacak
    }


    public function update(Request $request, $id)
    {
        $status = "success";
        $message = "Site Bilgileri Kaydedildi";

        try {
            $setting = SiteSettings::findOrFail($id);

            $uploadPath = public_path("images/site");

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $extension = $file->guessExtension();
                $fileName = "logo." . $extension;

                if ($setting->favicon != null && !Str::contains($setting->logo, "nophoto" && File::exists(public_path("images/site/").$setting->logo))) {
                    File::delete(public_path("images/site/").$setting->logo);
                }

                $file->move($uploadPath, $fileName);
                LogService::add("Site Setting Service Update","success",$setting->site_name." Logo Yüklendi");
                $setting->update([
                    "logo" => $fileName
                ]);
                LogService::add("Site Setting Service Update","success",$setting->site_name." Logo Kaydedildi");

            }

            if ($request->hasFile('favicon')) {
                $file = $request->file('favicon'); // ✅ düzeltildi
                $extension = $file->guessExtension();
                $fileName = "favicon." . $extension;
                $file->move($uploadPath, $fileName);

                if ($setting->favicon != null && !Str::contains($setting->favicon, "nophoto" && File::exists(public_path("images/site/").$setting->favicon))) {
                    File::delete(public_path("images/site/").$setting->favicon);
                }

                LogService::add("Site Setting Service Update","success",$setting->site_name." Favicon Yüklendi");
                $setting->update([
                    "favicon" => $fileName
                ]);
                LogService::add("Site Setting Service Update","success",$setting->site_name." Favicon Kaydedildi");

            }

            if ($request->hasFile('footer_logo')) {
                $file = $request->file('footer_logo'); // ✅ düzeltildi
                $extension = $file->guessExtension();
                $fileName = "footer-logo." . $extension;

                if ($setting->favicon != null && !Str::contains($setting->footer_logo, "nophoto" && File::exists(public_path("images/site/").$setting->footer_logo))) {
                    File::delete(public_path("images/site/").$setting->footer_logo);
                }

                $file->move($uploadPath, $fileName);
                LogService::add("Site Setting Service Update","success",$setting->site_name." Footer Logo Yüklendi");
                $setting->update([
                    "footer_logo" => $fileName
                ]);
                LogService::add("Site Setting Service Update","success",$setting->site_name." Footer Logo Kaydedildi");
            }
            $setting->update([
                "site_name"=>$request->site_name,
                "seo_title"=>$request->seo_title,
                "description"=>$request->description,
                "head_code"=>$request->head_code,
                "header_code"=>$request->header_code,
                "footer_code"=>$request->footer_code
            ]);

            return ["status"=> $status, "message"=> $message];

        } catch (\Throwable $exception) {
            return ["status"=> "error", "message"=> "Hata oluştu: " . $exception->getMessage()];
        }
    }


}
