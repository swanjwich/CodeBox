<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Snippet extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'content',
        'visibility',
        'category_id',
    ];

    protected $casts = [
        'is_favorite' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'snippet_tags')->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'snippet_category');
    }

}
