<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;
class Artist extends Model
{
    use HasFactory; protected $fillable = ['id','name','birthday','photoUrl','biography']; protected $table = 'artist'; public $timestamps = false;
    public function Role()
    {
        return $this->belongsToMany(Role::class);
    }
    
}
