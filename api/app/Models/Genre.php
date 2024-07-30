<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory; protected $fillable = ['id','title']; protected $table = 'genre'; public $timestamps = false;
}