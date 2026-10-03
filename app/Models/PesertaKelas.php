<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesertaKelas extends Model
{
    protected $table    = 'peserta_kelas';
    protected $fillable = ['user_id', 'kelas_id', 'label'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
