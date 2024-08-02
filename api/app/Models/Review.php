<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'userId', 'movieId', 'content', 'stars', 'createdAt'];
    protected $table = 'review';
    public $timestamps = false;

    public function User()
    {
        return $this->belongsTo(User::class, 'userId');
    }
    public function Reviewevaluation()
    {
        return $this->hasMany(ReviewEvaluation::class, 'reviewId');
    }
}
