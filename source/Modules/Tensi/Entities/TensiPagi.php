<?php

namespace Modules\Tensi\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\HariPerawatan\Entities\HariPerawatan;

class TensiPagi extends Model
{
    protected $table = 'tensi_pagi';

    protected $fillable = [
        'id_hari_perawatan', 'tensi_atas', 'tensi_bawah', 'temperatur', 'id_petugas'
    ];

    public function hari_perawatan()
    {
        return $this->belongsTo(HariPerawatan::class, 'id_hari_perawatan', 'id');
    }
}
