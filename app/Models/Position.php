<?php

    namespace app\Models;

    use Illuminate\Support\Facades\DB;
    use Illuminate\Database\Eloquent\Model;

    class Position extends Model
    {
        /**
         * The table associated with the model.
         *
         * @var string
         */
        protected $table = 'Positions';

        /**
         * The primary key associated with the table.
         *
         * @var string
         */
        protected $primaryKey = 'positionID';

        /**
         * The attributes that are mass assignable.
         *
         * @var array<int, string>
         */
        protected $fillable = [
            'position_name',
            'zones_list',
        ];

        public $timestamps = null;
    }
