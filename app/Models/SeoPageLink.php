<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoPageLink extends Model
{
    protected $fillable = [
        'seo_page_id',
        'block_id',
        'linked_page_title',
        'linked_page_url',
    ];


  // SeoPageLink.php
public function block()
{
    return $this->belongsTo(SeoPageBlock::class, 'block_id');
}

public function page()
{
    return $this->belongsTo(SeoPage::class, 'seo_page_id');
}

    
}