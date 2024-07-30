<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory; protected $fillable = ['id','movieId','artistId','roleId']; protected $table = 'credit'; public $timestamps = false;
}
