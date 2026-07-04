<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultiUploadExample extends Model
{
    use HasFactory;

    protected $table = 'multi_upload_examples';
    protected $primaryKey = 'id_multi_upload';
    
    protected $fillable = [
        'image_path',
        'alt_text',
    ];
}
