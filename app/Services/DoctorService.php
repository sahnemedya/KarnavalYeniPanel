<?php

namespace App\Services;

use App\Models\Blade;
use App\Models\Category;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpParser\Comment\Doc;

class DoctorService
{
    protected CommonService $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
        //$this->commonService-> olarak kullanılacak
    }

    public function store(Request $request)
    {
        $status = 'success';
        $message = 'Doktor Kaydedildi';

        try {
            $doctor = Doctor::create([
                'title' => $request->title,
                'description' => $request->description,
                'slug' => $request->slug,
                'content' => $request->content_text,
                'medical_unit' => $request->medical_unit,
                'medical_unit2' => $request->medical_unit2 ?: null,
                'hit' => $request->hit
            ]);

            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $extension = $imageFile->guessExtension();
                $fileName = $doctor->slug . '-2.' . $extension;
                $this->commonService->uploadFile(config('constants.doctor_path'), $imageFile, $fileName);
                $doctor->update([
                    "image" => $fileName,
                ]);
                LogService::add("Side Menu Element Service Store", "success", $doctor->title . ' Sayfa Resmi Kaydedildi');
            }
            if ($request->hasFile('image2')) {
                $imageFile = $request->file('image2');
                $extension = $imageFile->guessExtension();
                $fileName = $doctor->slug . '2-.' . $extension;
                $this->commonService->uploadFile(config('constants.doctor_path'), $imageFile, $fileName);
                $doctor->update([
                    "image2" => $fileName,
                ]);
                LogService::add("Side Menu Element Service Store", "success", $doctor->title . ' Sayfa Resmi Kaydedildi');
            }

            return [
                "status" => $status,
                "message" => $message,
            ];

        } catch (\Throwable $exception) {
            $status = 'error';
            $message = "Doktor Bilgisi Kaydedilemedi";
            LogService::add("Doctor Service Store", $status, $message . " => " . $exception->getMessage());
            return [
                "status" => $status,
                "message" => $message,
            ];
        }

    }

    public function edit($id)
    {
        $status = 'success';
        $message = 'Doktor Bulundu.';
        try {
            $doctor = Doctor::findOrFail($id);
            $medicalUnit = Category::where("is_medical_unit", 1)->first();
            return [
                "status" => $status,
                "message" => $message,
                "doctor" => $doctor,
                "medicalUnit" => $medicalUnit,
            ];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = "Doktor Bulunamadı.";
            LogService::add("Doctor Service Edit", $status, $message . " => " . $exception->getMessage());
            return [
                "status" => $status,
                "message" => $message,
            ];
        }
    }

    public function update(Request $request, $id)
    {
        $status = 'success';
        $message = 'Doktor Bilgileri Güncellendi';
//        $doctor = NULL;
        try {
            $doctor = Doctor::findOrFail($id);

            // resim1 silinecekse
            if ($request->removeImage) {
                if ($doctor->image) {
                    $this->commonService->deleteFile(config('constants.doctor_path'), $doctor->image);
                }
                $doctor->update(["image" => NULL]);
            }
            //yeni resim1 yüklenecekse
            if ($request->hasFile("image")) {
                if ($doctor->image) {
                     $this->commonService->deleteFile(config("constants.doctor_path"), $doctor->image);
                }
                $image = $request->file("image");
                $extension = $image->guessExtension();
                $imageName  = $doctor->slug . '-' . Str::lower(Str::random(4)) . '.' . $extension;
                 $this->commonService->uploadFile(config("constants.doctor_path"), $image, $imageName);
                $doctor->update(["image" => $imageName]);
            }


            // resim2 silinecekse
            if ($request->removeImage2) {
                if ($doctor->image2) {
                    $this->commonService->deleteFile(config('constants.doctor_path'), $doctor->image2);
                }
                $doctor->update(["image" => NULL]);
            }
            //yeni resim2 yüklenecekse
            if ($request->hasFile("image2")) {
                if ($doctor->image2) {
                    $this->commonService->deleteFile(config("constants.doctor_path"), $doctor->image2);
                }
                $image2 = $request->file("image");
                $extension = $image2->guessExtension();
                $image2Name  = $doctor->slug . '-' . Str::lower(Str::random(4)) . '.' . $extension;
                $this->commonService->uploadFile(config("constants.doctor_path"), $image2, $image2Name);
                $doctor->update(["image2" => $image2Name]);
            }
            // Resmin adı slug değişiminden dolayı değişecekse
            if (!$request->removeImage && !$request->hasFile('image') && $doctor->image) {
                if ($request->slug != $doctor->slug) {
                    $extension     = pathinfo($doctor->image, PATHINFO_EXTENSION);
                    $newImageName  = $request->slug . '-' . Str::lower(Str::random(4)) . '.' . $extension;
                    $this->commonService->renameFile(config('constants.doctor_path'), $doctor->image, $newImageName);
                    $doctor->update(["image" => $newImageName]);
                }
            }
            if (!$request->removeImage2 && !$request->hasFile('image2') && $doctor->image2) {
                if ($request->slug != $doctor->slug) {
                    $extension     = pathinfo($doctor->image2, PATHINFO_EXTENSION);
                    $newImageName2 = $request->slug . '-2-' . Str::lower(Str::random(4)) . '.' . $extension;
                    $this->commonService->renameFile(config('constants.doctor_path'), $doctor->image2, $newImageName2);
                    $doctor->update(["image2" => $newImageName2]);
                }
            }
            $doctor->update([
                'title' => $request->title,
                'description' => $request->description,
                'slug' => $request->slug,
                'content' => $request->content_text,
                'medical_unit' => $request->medical_unit,
                'medical_unit2' => $request->medical_unit2 ?: null,
                'hit' => $request->hit

            ]);
            LogService::add("Doctor Service Update", $status, $message);
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            $status = 'error';
            $message = "Doktor Bilgisi Güncellenemedi.";
            if ($doctor != NULL) {
                LogService::add("Doctor Service Update", $status, $doctor->title . $message . " => " . $exception->getMessage());
            } else {
                LogService::add("Doctor Service Update", $status, $message . " => " . $exception->getMessage());
            }
            return ["status" => $status, "message" => $message];

        }

    }

    public function destroy($id)
    {
        $status = 'success';
        $message = 'Doktor Bilgisi Silindi.';
        try {
            $doctor = Doctor::findOrFail($id);
            $doctor->delete();
            $message = $doctor->title . ' Sayfa Silindi';
            LogService::add("Doctor Service Destroy", $status, $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = "Doktor Bilgisi Silinemedi.";
            LogService::add("Doctor Service Destroy", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function deleted()
    {
        $status = 'success';
        $message = 'Silinen Doktorlar Listelendi.';
        try {
            $doctors = Doctor::onlyTrashed()->get();
            return [
                "status" => $status,
                "message" => $message,
                "doctors" => $doctors
            ];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = "Silinen Doktorlar Listelenemedi.";
            LogService::add("Doctor Service Deleted", $status, $message . " => " . $exception->getMessage());
            return [
                "status" => $status,
                "message" => $message,
            ];
        }
    }

    public function forceDelete($id)
    {
        $status = 'success';
        $message = 'Doktor Silindi.';
        try {
            $doctor = Doctor::onlyTrashed()->where('id', $id)->firstOrFail();
            $doctor->forceDelete();
            if($doctor->image){
                $this->commonService->deleteFile(config('constants.doctor_path'), $doctor->image);
            }
            if($doctor->image2){
                $this->commonService->deleteFile(config('constants.doctor_path'), $doctor->image2);
            }
            $message = $doctor->title . ' Doktor Silindi.';
            LogService::add("Doctor Service ForceDelete", $status, $message);
            return ['status' => $status, 'message' => $message];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = $doctor->title . ' Kategori Silinemedi.';
            LogService::add("Doctor Service ForceDelete", $status, $message . ' => ' . $exception->getMessage());
            return ['status' => $status, 'message' => $message];
        }
    }

    public function restore($id)
    {
        $status = 'success';
        $message = 'Doktor Geri Yüklendi.';
        try {
            $doctor = Doctor::onlyTrashed()->where('id', $id)->firstOrFail();
            $doctor->restore();
            $message = $doctor->title . ' Kategori Geri Yüklendi.';
            LogService::add("Doctor Service Restore", $status, $message);
            return ['status' => $status, 'message' => $message];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'Doktor Geri Yüklenemedi.';
            LogService::add("Doctor Service Restore", $status, $message . ' => ' . $exception->getMessage());
            return ['status' => $status, 'message' => $message];
        }
    }

    public function publishPage($id)
    {
        $status = "success";
        $message = NULL;
        try {
            $doctor = Doctor::findOrFail($id);
            if ($doctor->published == 1) {
                $doctor->update(["published" => 0]);
                $message = $doctor->title . ' Doktor Yayından Kaldırıldı.';
                LogService::add("doctor Service PublishPage", $status, $message);
            } else {
                $doctor->update(["published" => 1]);
                $message = $doctor->title . ' Doktor Yayınlandı.';
                LogService::add("doctor Service PublishPage", $status, $message);
            }
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = 'İşlem Yapılamadı';
            LogService::add("doctor Service PublishPage ", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }
    public function activate(Request $request, $id)
    {
        try {
            $doctor = Doctor::findOrFail($id);
            switch ($request->type) {
                case "show":
                    return $this->toggleVisibility($doctor, 'show', 'sayfa');
                case "show_homepage":
                    return $this->toggleVisibility($doctor, 'show_homepage', 'Ana Sayfa');

                default:
                    LogService::add("Doctor Service Activate", "error", "Geçersiz İşlem");
                    return ['status' => 'error', 'message' => 'Geçersiz işlem türü.'];
            }
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'Gösterim Hatası => ' . $exception->getMessage();
            LogService::add('doctor Service Activate', $status, $message);
            return ['status' => $status, 'message' => $message];
        }
    }
    public function toggleVisibility(Doctor $doctor, string $field, string $label)
    {
        $status = 'success';
        $message = "{$doctor->title} Doktor {$label} Gösterimi";

        try {
            $newValue = $doctor->$field == 1 ? 0 : 1;
            $action = $newValue ? 'Açıldı' : 'Kapatıldı';

            $doctor->update([$field => $newValue]);
            $message .= " $action";

            LogService::add("Doctor Service ToggleVisibility", $status, $message);

            return ['status' => $status, 'message' => $message];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message .= " Hatası => " . $exception->getMessage();
            LogService::add("Doctor Service ToggleVisibility", $status, $message);
            return ['status' => $status, 'message' => $message];
        }
    }

    public function getAllActiveDoctors()
    {
        return Doctor::where('show',1)->with(['medicalUnit','medicalUnit2'])->orderBy('hit','asc')->get();
    }

    public function findBySlug($slug)
    {
        return Doctor::where('slug',$slug)->where('show',1)->with(['medicalUnit','medicalUnit2'])->firstOrFail();
    }
}
