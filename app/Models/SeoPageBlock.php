<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoPageBlock extends Model
{
    protected $fillable = [
        'seo_page_id',
        'block_type',
        'heading_level',
        'content',
        'sort_order',
    ];

    public function page()
    {
        return $this->belongsTo(SeoPage::class);
    }

 public function images()
{
    return $this->hasMany(SeoPageImage::class, 'block_id')->orderBy('sort_order');
}

public function links()
{
    return $this->hasMany(SeoPageLink::class, 'block_id');
}

}