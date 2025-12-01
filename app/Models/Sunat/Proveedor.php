<?php

namespace App\Models\Sunat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 't_logm_provee';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $guarded = [];

    protected $primaryKeys = ['entcodigo', 'uejcodigo'];

    protected function setKeysForSaveQuery($query)
    {
        foreach ($this->primaryKeys as $key) {
            $query->where($key, '=', $this->getAttribute($key));
        }
        return $query;
    }
}
