<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function blogs(){
        return $this->belongsToMany(Blog::class, 'category_blog', 'category_id', 'blog_id' );
    }
}
