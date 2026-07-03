<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperFile
 */
class File extends Model
{
    protected $table = 'extension.file';
    protected $primaryKey = 'file_code';
    public $timestamps = false;
    protected $hidden = [];
    protected $attributes = [];
    protected $fillable = [
        'file_byte',
        'file_conf',
        'file_daho',
        'file_heig',
        'file_mity',
        'file_path',
        'file_stde',
        'file_widt',
    ];
}
