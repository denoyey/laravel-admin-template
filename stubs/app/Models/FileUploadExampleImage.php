<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUploadExampleImage extends Model
{
    use HasFactory;

    protected $table = 'file_upload_example_images';
    protected $primaryKey = 'id_file_upload_image';

    protected $fillable = [
        'id_file_upload',
        'image_path',
        'alt_text',
        'is_cover',
    ];

    public function fileUploadExample()
    {
        return $this->belongsTo(FileUploadExample::class, 'id_file_upload', 'id_file_upload');
    }
}
