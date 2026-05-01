<?php

namespace App\Services;

use App\Models\Blade;
use App\Models\Gallery;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryService
{
    protected CommonService $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
        //$this->commonService-> olarak kullanılacak
    }

    public function create($pageId)
    {
        try {
            $page = Page::findOrFail($pageId);

            return ["page" => $page, "status" => "success"];
        } catch (\Exception $exception) {
            return ["status" => "error"];
        }
    }

    public function store(Request $request)
    {
        $status = "success";
        $message = "Resim Yüklendi";
        try {
            $page = Page::findOrFail($request->page_id);
            $fileName = NULL;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $extension = $file->guessExtension();
                $fileName = Str::slug($page->title) . '-' . Str::lower(Str::random(7)) . '.' . $extension;
                $uploadFile = $this->commonService->uploadFile(config("constants.gallery_path"), $file, $fileName);
            }
            if ($fileName) {
                $gallery = Gallery::create([
                    "page_id" => $page->id,
                    "image" => $fileName
                ]);
                LogService::add("Gallery Service Store", $status, $message);
                return ["status" => $status, "message" => $message];
            } else {
                $status = "error";
                $message = "Resim Yüklenirken Sorun Oluştu.";
                LogService::add("Gallery Service Store", $status, $message);
                return ["status" => $status, "message" => $message];
            }
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Resim Yüklenemedi.";
            LogService::add("Gallery Service Store", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function pageGallery($pageId)
    {
        try {
            $page = Page::find($pageId);

            if (!$page) {
                return [
                    'status' => 'error',
                    'message' => 'Sayfa bulunamadı'
                ];
            }

            // Önce sayfanın kendi galerisini çek
            $galleries = Gallery::where('page_id', $pageId)
                ->orderBy('created_at', 'desc')
                ->get();

            $isInherited = false;
            $sourcePage  = null;

            // Kendi galerisi yoksa VE çeviri sayfasıysa, kaynak sayfanın galerisine düş
            if ($galleries->isEmpty() && $page->translation_of) {
                $sourcePage = Page::find($page->translation_of);

                if ($sourcePage) {
                    $galleries = Gallery::where('page_id', $sourcePage->id)
                        ->orderBy('created_at', 'desc')
                        ->get();

                    if ($galleries->isNotEmpty()) {
                        $isInherited = true;
                    }
                }
            }

            return [
                'status'       => 'success',
                'page'         => $page,
                'galleries'    => $galleries,
                'isInherited'  => $isInherited,   // View'da "Kaynak galeriden gösteriliyor" rozeti için
                'sourcePage'   => $sourcePage,    // Hangi sayfadan miras alındığını göstermek için
            ];
        } catch (\Throwable $exception) {
            LogService::add("Gallery Service PageGallery", 'error', "Sayfa Galerisi Çekilemedi => " . $exception->getMessage());
            return [
                'status'  => 'error',
                'message' => 'Sayfa Galeri Çekilemedi'
            ];
        }
    }

    public function destroy($galleryId)
    {
        $status = "success";
        $message = "Sayfa Galeri Resmi Silindi.";

        try {
            $gallery = Gallery::findOrFail($galleryId);
            $gallery->delete();
            $delete = $this->commonService->deleteFile(config("constants.gallery_path"), $gallery->image);
            LogService::add("Gallery Service Destroy", $status, $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Sayfa Galeri Resmi Silinemedi";
            LogService::add("Gallery Service Destroy", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function destroyPageGallery($galleryId)
    {
        $status = "success";
        $message = "Sayfa Galerisi Silindi.";

        try {
            Gallery::where("page_id", $galleryId)->delete();

            LogService::add("Gallery Service Destroy", $status, $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Sayfa Galerisi Silinemedi";
            LogService::add("Gallery Service Destroy", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }
}
