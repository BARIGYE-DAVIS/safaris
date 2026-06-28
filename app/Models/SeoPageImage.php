<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoPageImage extends Model
{
    protected $fillable = [
        'block_id',
        'image_path',
        'alt_text',
        'sort_order',
    ];

  

    // SeoPageImage.php
public function block()
{
    return $this->belongsTo(SeoPageBlock::class, 'block_id');
}
}