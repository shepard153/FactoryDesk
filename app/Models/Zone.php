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
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'zone_name',
    ];

    public $timestamps = null;
}
