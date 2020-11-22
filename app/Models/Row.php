<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Row extends Model
{
    use HasFactory;

    protected $fillable = ['import_id', 'import_name', 'import_date', 'file_id'];

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id');
    }
}
