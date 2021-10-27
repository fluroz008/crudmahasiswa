<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa;

class user extends Model
{
    use HasFactory;

    protected $table = 'user';
    protected $fillable = [
        'id',
        'nama',
        'password',
        'email',
        'foto'
    ];

    protected $hidden = [
        'password'
    ];

    public function mahasiswa(){

        return $this->hasOne(Mahasiswa::class, 'user_id', 'id'); 
    }
}
