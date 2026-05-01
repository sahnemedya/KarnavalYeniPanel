<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CommonService
{

    /**
     * Bir dosyanın mevcut olup olmadığını kontrol etmek için kullanabilirsiniz.
     * Storage::disk('public') ana dizini içerisinde işlem yapılır.
     * @param string $path
     * @param string $fileName
     * @return bool
     */
    public function fileExists(string $path, string $fileName): bool
    {
        return Storage::disk('public')->exists($path . DIRECTORY_SEPARATOR . $fileName);
    }

    /**
     *  Dizine dosya yüklemek için kullanabilirsiniz.
     *  Storage::disk('public') ana dizini içerisinde işlem yapılır.
     * @param string $path
     * @param UploadedFile $file
     * @param string $fileName
     * @return bool
     */
    public function uploadFile(string $path, UploadedFile $file, string $fileName): bool
    {
        try {
            $upload = Storage::disk("public")->putFileAs($path, $file, $fileName);
            return true;
        } catch (\Throwable $e) {
            LogService::add("CommonService UploadFile", "error", $e->getMessage() . '[FileName: ' . $fileName . ']');
            return false;
        }
    }

    /**
     * Dizinden dosya silmek için kullanabilirsiniz.
     * Storage::disk('public') ana dizini içerisinde işlem yapılır.
     * @param string $path
     * @param string $fileName
     * @return bool
     */
    public function deleteFile(string $path, string $fileName): bool
    {
        if ($this->fileExists($path, $fileName)) {
            try {
                Storage::disk('public')->delete($path . DIRECTORY_SEPARATOR . $fileName);
                return true;
            } catch (\Throwable $e) {
                LogService::add("CommonService DeleteFile", "error", $e->getMessage() . '[FileName: ' . $fileName . ']');
                return false;
            }
        } else {
            LogService::add("CommonService DeleteFile", "warning", " Silinecek Dosya Bulunamadı" . '[FileName: ' . $fileName . ']');
            return true;
        }

    }

    /**
     * Dizinden dosyayı başka bir dizine taşımak için kullanabilirsiniz.
     * Storage::disk('public') ana dizini içerisinde işlem yapılır.
     * @param string $fileName
     * @param string $from
     * @param string $to
     * @return bool
     */
    public function moveFile(string $fileName, string $from, string $to): bool
    {
        if ($this->fileExists($from, $fileName)) {
            try {
                Storage::disk('public')->move($from . DIRECTORY_SEPARATOR . $fileName, $to . DIRECTORY_SEPARATOR . $fileName);
                return true;
            } catch (\Throwable $e) {
                LogService::add("CommonService MoveFile", "error", $e->getMessage() . '[FileName: ' . $fileName . ']');
                return false;
            }
        } else {
            LogService::add("CommonService MoveFile", "warning", "Taşınacak Dosya Bulunamadı" . '[FileName: ' . $fileName . ']');
            return true;
        }

    }

    /**
     * Dizindeki dosyanın adını değiştirmek için kullanabilirsiniz.
     * Storage::disk('public') ana dizini içerisinde işlem yapılır.
     * @param string $path
     * @param string $oldName
     * @param string $newName
     * @return bool
     */
    public function renameFile(string $path, string $oldName, string $newName): bool
    {
        if ($this->fileExists($path, $oldName)) {
            try {
                Storage::disk('public')->move($path . DIRECTORY_SEPARATOR . $oldName, $path . DIRECTORY_SEPARATOR . $newName);
                return true;
            } catch (\Throwable $e) {
                LogService::add("CommonService RenameFile", "error", $e->getMessage() . '[FileName: ' . $oldName . ']');
                return false;
            }

        } else {
            LogService::add("CommonService RenameFile", "warning", "Yeniden Adlandırılacak Dosya Bulunamadı" . '[FileName: ' . $oldName . ']');
            return true;
        }
    }


    /**
     *  Dizinde bulanan dosyayı farklı bir dizine farklı bir isimde kaydetmek için kullanabilirsiniz.
     *  Storage::disk('public') ana dizini içerisinde işlem yapılır.
     * @param string $from
     * @param string $oldName
     * @param string $to
     * @param string $newName
     * @return bool
     */
    public function moveAndRenameFile(string $from, string $oldName, string $to, string $newName): bool
    {
        if ($this->fileExists($from, $oldName)) {
            try {
                Storage::disk('public')->move($from . DIRECTORY_SEPARATOR . $oldName, $to . DIRECTORY_SEPARATOR . $newName);
                return true;
            } catch (\Throwable $e) {
                LogService::add("CommonService MoveFile", "error", $e->getMessage() . '[FileName: ' . $oldName . ']');
                return false;
            }
        } else {
            LogService::add("CommonService MoveAndRenameFile", "warning", " Dosya Bulunamadı" . '[FileName: ' . $oldName . ']');
            return true;
        }
    }

    /**
     * Verilen metni URL uyumlu slug formatına çevirir.
     * @param string $text
     * @return string
     */
    public function slugMaker(string $text): string
    {
        return Str::slug($text, "-");
    }


}
