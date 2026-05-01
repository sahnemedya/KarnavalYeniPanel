<?php

namespace App\Services;

use App\Models\ApiKeys;
use App\Models\Blade;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PanelMenuSettingService
{
    protected CommonService $commonService;

    public function update($id)
    {
        $status = "success";
        $message = "Kategori ShowPanel Aktifleştirildi";
        try {
            $category = Category::findOrFail($id);
            if ($category->show_panel == 1) {
                $category->update(['show_panel' => 0]);
                $message = "Kategori ShowPanel Pasif Edildi";
            } else {
                $category->update(['show_panel' => 1]);
            }
            LogService::add("Panel Menu Setting Service Update", $status, $category->name . " " . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Kategori ShowPanel Değiştirilemedi";
            LogService::add("Panel Menu Setting Service Update", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

}
