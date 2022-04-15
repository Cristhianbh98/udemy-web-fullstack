<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model {
  use HasFactory;

  protected $table = 'posts';
  protected $fillable = ['title', 'content', 'category_id'];

  public function user() {
    return $this->belongsTo('App\models\User', 'user_id');
  }

  public function category() {
    return $this->belongsTo('App\models\Category', 'category_id');
  }
}
