<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CategoryService
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
        $message = 'Kategori Kaydedildi.';
        try {
            $imageFile = NULL;
            $imageExtension = NULL;
            $imageName = Str::slug($request->name, "-") . '-' . Str::lower(Str::random(4));
            if ($request->hasFile('category_image')) {
                $imageFile = $request->file('category_image');
                $imageName .= '.' . $imageFile->guessExtension();
            }
            $category = Category::create([
                'name' => $request->name,
                'note' => $request->note,
                'hit' => $request->hit,
                'parent_category' => $request->parent_category,
                'lang_id' => $request->lang_id,
                'translation_of' => $request->translation_of
            ]);
            LogService::add("Catgory Service Store", $status, $message);
            if ($imageFile) {
                $upload = $this->commonService->uploadFile(config('constants.category_path'), $imageFile, $imageName);
                $category->update(['image' => $imageName]);
            }

            return [
                'status' => $status,
                'message' => $message
            ];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'Kaydedilemedi.';
            LogService::add('Category Service Store', $status, $message . ' => ' . $exception->getMessage());
            return [
                'status' => $status,
                'message' => $message
            ];
        }

    }

    public function update(Request $request, $id)
    {
        $status = 'success';
        $message = 'Kategori Güncellendi.';

        try {
            $category = Category::findOrFail($id);
            $oldImageName = $category->image;
            $newImageName = $oldImageName;

            if ($request->hasFile('category_image')) {
                $file = $request->file('category_image');
                $slug = Str::slug($request->name, "-") . '-' . Str::lower(Str::random(4));
                $newImageName = $slug . '.' . $file->guessExtension();
            }
            if ($request->removeImage && !$request->hasFile('category_image')) {
                $this->removeImage($id);
                $newImageName = null;
            }
            if (!$request->removeImage && !$request->hasFile('category_image') && $category->image != NULL) {
                $extension = pathinfo($oldImageName, PATHINFO_EXTENSION);

                if (Str::slug($request->name, "-") != Str::slug($category->name, "-")) {
                    $newImageName = Str::slug($request->name, "-") . '-' . Str::lower(Str::random(4)) . '.' . $extension;
                    $this->commonService->renameFile(config('constants.category_path'), $category->image, $newImageName);
                }
            }

            $category->update([
                "name" => $request->name,
                "note" => $request->note,
                "image" => $newImageName,
                "hit" => $request->hit,
                "parent_category" => $request->parent_category,
                "translation_of" => $request->translation_of,
                "lang_id" => $request->lang_id
            ]);

            if ($request->hasFile('category_image')) {
                $this->commonService->deleteFile(config('constants.category_path'), $oldImageName);
                $this->commonService->uploadFile(config('constants.category_path'), $file, $newImageName);
                LogService::add("Category Service Update", "success", $category->name . " Kategori Resmi Güncellendi");
            }

            LogService::add("Category Service Update", $status, $message);

        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'Güncellenemedi.';
            LogService::add('Category Service Update', $status, $message . ' => ' . $exception->getMessage());
            return [
                'status' => $status,
                'message' => $message
            ];
        }

        return [
            'status' => $status,
            'message' => $message
        ];
    }

    public function destroy($id)
    {
        $status = 'success';
        $message = 'Kategori Silindi.';
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            $message = $category->name . ' Kategori Silindi.';
            LogService::add("Category Service Destroy", $status, $message);
            return ['status' => $status, 'message' => $message];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = $category->name . ' Kategori Silinemedi.';
            LogService::add("Category Service Destroy", $status, $message . ' => ' . $exception->getMessage());
            return ['status' => $status, 'message' => $message];
        }
    }

    public function forceDelete($id)
    {
        $status = 'success';
        $message = 'Kategori Silindi.';
        try {
            $category = Category::onlyTrashed()->where('id', $id)->firstOrFail();
            $category->forceDelete();
            if($category->image){
                $this->commonService->deleteFile(config('constants.category_path'), $category->image);
            }
            $message = $category->name . ' Kategorisi Silindi.';
            LogService::add("Category Service ForceDelete", $status, $message);
            return ['status' => $status, 'message' => $message];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = $category->name . ' Kategori Silinemedi.';
            LogService::add("Category Service ForceDelete", $status, $message . ' => ' . $exception->getMessage());
            return ['status' => $status, 'message' => $message];
        }
    }

    public function restore($id)
    {
        $status = 'success';
        $message = 'Kategori Geri Yüklendi.';
        try {
            $category = Category::onlyTrashed()->where('id', $id)->firstOrFail();
            $category->restore();
            $message = $category->name . ' Kategori Geri Yüklendi.';
            LogService::add("Category Service Restore", $status, $message);
            return ['status' => $status, 'message' => $message];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'Kategori Geri Yüklenemedi.';
            LogService::add("Category Service Restore", $status, $message . ' => ' . $exception->getMessage());
            return ['status' => $status, 'message' => $message];
        }
    }

    public function activate(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);
            switch ($request->type) {
                case "show_menu":
                    return $this->toggleVisibility($category, 'show_menu', 'Menü');
                case "show_homepage":
                    return $this->toggleVisibility($category, 'show_homepage', 'Ana Sayfa');
                case "show_footer":
                    return $this->toggleVisibility($category, 'show_footer', 'Footer');
                default:
                    LogService::add("Category Service Activate", "error", "Geçersiz İşlem");
                    return ['status' => 'error', 'message' => 'Geçersiz işlem türü.'];
            }
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'Gösterim Hatası => ' . $exception->getMessage();
            LogService::add('Category Service Activate', $status, $message);
            return ['status' => $status, 'message' => $message];
        }
    }

    public function toggleVisibility(Category $category, string $field, string $label)
    {
        $status = 'success';
        $message = "{$category->name} Kategori {$label} Gösterimi";

        try {
            $newValue = $category->$field == 1 ? 0 : 1;
            $action = $newValue ? 'Açıldı' : 'Kapatıldı';

            $category->update([$field => $newValue]);
            $message .= " $action";

            LogService::add("Category Service ToggleVisibility", $status, $message);

            return ['status' => $status, 'message' => $message];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message .= " Hatası => " . $exception->getMessage();
            LogService::add("Category Service ToggleVisibility", $status, $message);
            return ['status' => $status, 'message' => $message];
        }
    }

    public function removeImage($id)
    {
        $status = 'success';
        $message = 'Kategori Resmi Silindi.';
        try {
            $category = Category::findOrFail($id);
            $deleteImage = $this->commonService->deleteFile(config("constants.category_path"), $category->image);

            if ($deleteImage) {
                $category->update(['image' => NULL]);
                LogService::add("Catgory Service RemoveImage", $status, $message);
                $this->reloadCache();
            }
            return true;
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'Silinemedi.';
            LogService::add('Category Service RemoveImage', $status, $message . ' => ' . $exception->getMessage());
            return false;
        }
    }

    public function reloadCache()
    {
        LogService::add("Category Service ReloadCache", "success", " Cache Güncellendi.");
        Cache::forget('category');
        Cache::remember('category', now()->addDay(), function () {
            return Category::all();
        });
        return true;
    }

}
