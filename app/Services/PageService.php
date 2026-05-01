<?php

namespace App\Services;

use App\Http\Requests\PageUpdateRequest;
use App\Models\Blade;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageService
{
    protected CommonService $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
        //$this->commonService-> olarak kullanılacak
    }

    public function store(Request $request)
    {
        $status = "success";
        $message = "Sayfa Kaydedildi";

        try {
            $page = Page::create([
                'title' => $request->title,
                'inside_title' => $request->inside_title,
                'slug' => $request->slug,
                'content' => $request->content_text,
                'hit' => $request->hit,
                'sezon_id' => $request->sezon_id,
                'category_id' => $request->category_id,
                "parent_page" => $request->parent_page,
                'blade_id' => $request->blade_id,
                'translation_of' => $request->translation_of,
                'lang_id' => $request->lang_id,
                'is_main' => 1
            ]);

            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $extension = $imageFile->guessExtension();
                $fileName = $page->slug . '-.' . $extension;
                $this->commonService->uploadFile(config('constants.page_path'), $imageFile, $fileName);
                $page->update([
                    "image" => $fileName,
                ]);
                LogService::add("Page Service Store", "success", $page->title . ' Sayfaya Resim Kaydedildi');
            }
            if ($request->hasFile('icon')) {
                $imageFile = $request->file('icon');
                $extension = $imageFile->guessExtension();
                $fileName = $page->slug . '-icon.' . $extension;
                $this->commonService->uploadFile(config('constants.page_path'), $imageFile, $fileName);
                $page->update(["icon" => $fileName]);
                LogService::add("Page Service Store", "success", $page->title . ' Sayfaya İcon Kaydedildi');
            }

            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Kaydedilemedi";
            LogService::add("Page Service Store ", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }

    }

    public function extraStoreUpdate(Request $request, string $id)
    {
        $status = "success";
        $message = "Sayfanın Extra Alanı Güncellendi";
        try {
            $page = Page::findOrFail($id);
            $updateData = [
                'link2' => $request->link2,
                'link3' => $request->link3,
                'video' => $request->video,
                'spotify' => $request->spotify,
                'heyzen' => $request->heyzen,
                'location' => $request->location,
            ];

            // 1. FILE (1. Dosya) İşlemi
            if ($request->has('remove_file')) { // Checkbox işaretliyse
                if ($page->file) {
                    $this->commonService->deleteFile(config('constants.file_path'), $page->file);
                }
                $updateData["file"] = NULL;
            }
            // Eğer yeni dosya yüklendiyse (önceki silme işlemini ezer)
            if ($request->filled('uploaded_file')) {
                if ($page->file) $this->commonService->deleteFile(config('constants.file_path'), $page->file);
                $updateData["file"] = $request->uploaded_file;
            }

            // 2. LINK (2. Dosya) İşlemi
            if ($request->has('remove_link')) {
                if ($page->link) {
                    // DİKKAT: Veritabanında adı 'link' ama klasörü 'file_path'
                    $this->commonService->deleteFile(config('constants.file_path'), $page->link);
                }
                $updateData["link"] = NULL;
            }
            if ($request->filled('uploaded_link')) {
                if ($page->link) $this->commonService->deleteFile(config('constants.file_path'), $page->link);
                $updateData["link"] = $request->uploaded_link;
            }

            // 3. SES İşlemi
            if ($request->has('remove_ses')) {
                if ($page->ses) {
                    $this->commonService->deleteFile(config('constants.voice_path'), $page->ses);
                }
                $updateData["ses"] = NULL;
            }
            if ($request->filled('uploaded_ses')) {
                if ($page->ses) $this->commonService->deleteFile(config('constants.voice_path'), $page->ses);
                $updateData["ses"] = $request->uploaded_ses;
            }

            $page->update($updateData);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            return ["status" => "error", "message" => "Hata: " . $exception->getMessage()];
        }
    }
    public function update(Request $request, $id)
    {
        $status = "success";
        $message = "Sayfa Güncellendi";

        try {
            $page = Page::findOrFail($id);

            // ======================================================
            // 1. IMAGE İŞLEMLERİ (Sayfa Resmi)
            // ======================================================
            if ($request->has('remove_image')) {
                if ($page->image) {
                    $this->commonService->deleteFile(config('constants.page_path'), $page->image);
                }
                $page->update(["image" => NULL]);
            }

            if ($request->hasFile('image')) {
                if ($page->image) {
                    $this->commonService->deleteFile(config('constants.page_path'), $page->image);
                }
                $imageFile = $request->file('image');
                $extension = $imageFile->guessExtension();
                $fileName = $page->slug . '-' . Str::lower(Str::random(4)) . '.' . $extension;
                $this->commonService->uploadFile(config('constants.page_path'), $imageFile, $fileName);
                $page->update(["image" => $fileName]);
            }

            // ======================================================
            // 2. ICON İŞLEMLERİ (Sayfa İconu)
            // ======================================================
            if ($request->has('remove_icon')) {
                if ($page->icon) {
                    $this->commonService->deleteFile(config('constants.page_path'), $page->icon);
                }
                $page->update(["icon" => NULL]);
            }

            if ($request->hasFile('icon')) {
                if ($page->icon) {
                    $this->commonService->deleteFile(config('constants.page_path'), $page->icon);
                }
                $iconFile = $request->file('icon');
                $extension = $iconFile->guessExtension();
                $fileName = $page->slug . '-icon-' . Str::lower(Str::random(4)) . '.' . $extension;
                $this->commonService->uploadFile(config('constants.page_path'), $iconFile, $fileName);
                $page->update(["icon" => $fileName]);
            }

            if (!$request->has('remove_icon') && !$request->hasFile('icon') && $page->icon) {
                if ($request->slug != $page->slug) {
                    $extension = pathinfo($page->icon, PATHINFO_EXTENSION);
                    $newIconName = $request->slug . '-icon-' . Str::lower(Str::random(4)) . '.' . $extension;
                    $this->commonService->renameFile(config('constants.page_path'), $page->icon, $newIconName);
                    $page->update(["icon" => $newIconName]);
                }
            }



            // ======================================================
            // METİN VE İLİŞKİSEL VERİLERİN GÜNCELLENMESİ
            // ======================================================
            $page->update([
                'title' => $request->title,
                'inside_title' => $request->inside_title,
                'slug' => $request->slug,
                'content' => $request->content_text,
                'hit' => $request->hit,
                'sezon_id' => $request->sezon_id,
                'category_id' => $request->category_id,
                "parent_page" => $request->parent_page,
                'blade_id' => $request->blade_id,
                'translation_of' => $request->translation_of,
                'lang_id' => $request->lang_id,
            ]);

            LogService::add("Page Service Update", $status, $message);
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Sayfa Güncellenemedi";
            LogService::add("Page Service Update ", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function publishPage($id)
    {
        $status = "success";
        $message = NULL;
        try {
            $page = Page::findOrFail($id);
            if ($page->published == 1) {
                $page->update(["published" => 0]);
                $message = $page->title . ' Sayfa Yayından Kaldırıldı.';
                LogService::add("Page Service PublishPage", $status, $message);
            } else {
                $page->update(["published" => 1]);
                $message = $page->title . ' Sayfa Yayınlandı.';
                LogService::add("Page Service PublishPage", $status, $message);
            }
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = 'İşlem Yapılamadı';
            LogService::add("Page Service PublishPage ", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function activate(Request $request, $id)
    {
        try {
            $page = Page::findOrFail($id);
            switch ($request->type) {
                case "published":
                    return $this->toggleVisibility($page, 'published', 'Menü');
                case "show_homepage":
                    return $this->toggleVisibility($page, 'show_homepage', 'Ana Sayfa');
                case "show_sponsorluk":
                    return $this->toggleVisibility($page, 'show_sponsorluk', 'Sponsorluk');
                case "show_footer":
                    return $this->toggleVisibility($page, 'show_footer', 'Footer');
                default:
                    LogService::add("Page Service Activate", "error", "Geçersiz İşlem");
                    return ['status' => 'error', 'message' => 'Geçersiz işlem türü.'];
            }
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'Gösterim Hatası => ' . $exception->getMessage();
            LogService::add('Page Service Activate', $status, $message);
            return ['status' => $status, 'message' => $message];
        }
    }

    public function toggleVisibility(Page $page, string $field, string $label)
    {
        $status = 'success';
        $message = "{$page->title} Sayfa {$label} Gösterimi";

        try {
            $newValue = $page->$field == 1 ? 0 : 1;
            $action = $newValue ? 'Açıldı' : 'Kapatıldı';

            $page->update([$field => $newValue]);
            $message .= " $action";

            LogService::add("Page Service ToggleVisibility", $status, $message);

            return ['status' => $status, 'message' => $message];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message .= " Hatası => " . $exception->getMessage();
            LogService::add("Page Service ToggleVisibility", $status, $message);
            return ['status' => $status, 'message' => $message];
        }
    }

    public function destroy($id)
    {
        $status = "success";
        $message = 'Sayfa Silindi';
        try {
            $page = Page::findOrFail($id);
            $page->delete();
            $message = $page->title . ' Sayfa Silindi';
            LogService::add("Page Service Destroy", $status, $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Sayfa Silinemedi";
            LogService::add("Page Service Destroy ", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function restore($id)
    {
        $status = "success";
        $message = 'Sayfa Geri Yüklendi';
        try {
            $page = Page::onlyTrashed()->findOrFail($id);
            $page->restore();
            $message = $page->title . ' ' . $message;
            LogService::add("Page Service Restore", $status, $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Sayfa Geri Yüklenemedi";
            LogService::add("Page Service Restore ", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }

    }

    public function forceDelete($id)
    {
        $status = "success";
        $message = 'Sayfa Silindi';

        try {
            $page = Page::onlyTrashed()->findOrFail($id);
            $page->forceDelete();
            $message = $page->title . ' Sayfa Silindi';
            LogService::add("Page Service ForceDelete", $status, $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Sayfa Silinemedi";
            LogService::add("Page Service ForceDelete ", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }

    }

    public function showHomePage($id)
    {
        $status = "success";
        $message = "Anasayfaya Eklendi";
        try {
            $page = Page::findOrFail($id);
            if ($page->show_homepage == 1) {
                $page->update(["show_homepage" => 0]);
                $message = "Anasayfadan Kaldırıldı.";
            } else {
                $page->update(["show_homepage" => 1]);
                $message = "Anasayfaya Eklendi";
            }
            LogService::add("Side Menu Publish", $status, $page->title . " " . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Anasayfadan Kaldırıldı.";
            LogService::add("Side Menu Service Publish", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }


}
