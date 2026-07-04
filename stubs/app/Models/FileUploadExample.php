<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUploadExample extends Model
{
    use HasFactory;

    protected $table = 'file_upload_examples';
    protected $primaryKey = 'id_file_upload';

    protected $fillable = [
        'judul',
        'deskripsi',
        'cover_image',
    ];

    public function images()
    {
        return $this->hasMany(FileUploadExampleImage::class, 'id_file_upload', 'id_file_upload');
    }
}
