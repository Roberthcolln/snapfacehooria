<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    use HasFactory;
    protected $table = 'foto';
    protected $primaryKey = 'id_foto';
    public $timestamps = true;
    protected $fillable = [
        'nama_foto',
        'file_foto',
        'slug_foto',
        'user_id',
        'harga_foto',
    ];

    // app/Models/Foto.php

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
