<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $fillable = [
        'id',
        'user_id',
        'nim',
        'jurusan',
        'kelas',
        'angkatan'
    ];

    public function user(){

        return $this->belongsTo(User::class, 'user_id', 'id'); 

    }
}
