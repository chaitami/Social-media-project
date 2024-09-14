<?php

namespace App;

use App\Scopes\AdminShowDeleteScope;
use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    
    use SoftDeletes;

    protected $fillable = ['title', 'content', 'user_id'];

    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable')->dernier();
    }

    public function tags() {
        return $this->morphToMany('App\Tag', 'taggable')->withTimestamps();
    }

    public function image() {
        return $this->morphOne('App\Image', 'imageable');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function scopeMostCommented(Builder $query) 
    {
        return $query->withCount('comments')->orderBy('comments_count', 'desc');
    } 

    public function scopePostWithUserCommentsTags(Builder $query) {
        return $query->withCount('comments')->with(['user', 'tags']);
    }

    
    public static function boot() {
      
        static::addGlobalScope(new AdminShowDeleteScope);
        parent::boot();

        static::addGlobalScope(new LatestScope);

     }
}
