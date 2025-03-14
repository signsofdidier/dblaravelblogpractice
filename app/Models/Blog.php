<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory, softDeletes;

    //
    protected $fillable = [
        'title',
        'photo_id',
        'user_id',
        'description',
        'created_at',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function photo(){
        return $this->belongsTo(Photo::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class, 'category_blog', 'blog_id', 'category_id' );
    }
}
