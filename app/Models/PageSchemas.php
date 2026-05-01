<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSchemas extends Model
{
    // Tablo adını belirtiyoruz (Migration'da page_schemas olarak açmıştık)
    protected $table = 'page_schemas';

    protected $fillable = [
        'page_id',
        'schema_type',
        'additional_data',
        'is_active'
    ];

    // JSON veriyi otomatik diziye (array) çevirmek için
    protected $casts = [
        'additional_data' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Bu şemanın ait olduğu sayfa.
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }
}
