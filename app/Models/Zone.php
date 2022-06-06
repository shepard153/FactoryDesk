<?php

namespace app\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Zones';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'zoneID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'zone_name',
        'department_list',
    ];

    public $timestamps = null;
}
