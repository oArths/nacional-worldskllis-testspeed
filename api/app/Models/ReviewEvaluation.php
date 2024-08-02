<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewEvaluation extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'userId', 'reviewId', 'positive'];
    protected $table = 'reviewevaluation';
    public $timestamps = false;
public function Review(){
    return $this->hasOne(Review::class, 'reviewId');
}

}
