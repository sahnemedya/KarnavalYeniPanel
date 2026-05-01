<?php

namespace App\Services;



use App\Models\ReferenceType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReferenceTypeService
{
    protected CommonService $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function store(Request $request)
    {
        $status = "success";
        $message = "Referans Türü Kaydedildi";

        try {

            $referenceTypes = ReferenceType::create([
                'name' => $request->name,
                'hit' => $request->hit,
                'lang_id' => $request->lang_id,
            ]);

            LogService::add("Reference Type Service Store", $status, $message);
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Referans Türü Kaydedilemedi";
            LogService::add("Reference Type Service Store", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function destroy($id)
    {
        $status = "success";
        $message = "Refeans Türü Gecici Olarak Silindi";
        try {
            $referenceTypes = ReferenceType::findOrFail($id);
            $referenceTypes->delete();
            LogService::add("Reference Type Service Destroy", $status, $referenceTypes->name . " " . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Referans Türü Gecici Olarak Silinemedi";
            LogService::add("Reference Type Service Destroy", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }
    public function restore($id)
    {
        $status = "success";
        $message = 'Referans Türü Geri Yüklendi';
        try {
            $referenceTypes = ReferenceType::onlyTrashed()->findOrFail($id);
            $referenceTypes->restore();
            $message = $referenceTypes->title . ' ' . $message;
            LogService::add("Reference Type Service Restore", $status, $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Referans Türü Geri Yüklenemedi";
            LogService::add("Reference Type Service Restore ", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }

    }

    public function forceDelete($id)
    {
        $status = "success";
        $message = 'Referans Türü Kalıcı Olarak Silindi';

        try {
            $reference = ReferenceType::onlyTrashed()->findOrFail($id);
            $reference->forceDelete();
            $message = $reference->title . ' Referans Türü Kalıcı Olarak Silindi';

            LogService::add("Reference Type Service ForceDelete", $status, $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Referans Türü Kalıcı Olarak Silinemedi";
            LogService::add("Reference Type Service ForceDelete ", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }

    }
    public function update(Request $request, $id)
    {
        $status = "success";
        $message = "Referans Türü Başarıyla Güncellendi";

        try {
            $referenceTypes = ReferenceType::findOrFail($id);
            $referenceTypes->update([
                'name' => $request->name,
                'hit' => $request->hit,
                'lang_id' => $request->lang_id,
            ]);

            LogService::add("References Type Service Update", $status, $message);
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Referens Türü Güncellenemedi";
            LogService::add("References Type Service Update ", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

}
