<?php

    namespace App\Models;

    use Illuminate\Support\Facades\DB;
    use Illuminate\Database\Eloquent\Model;

    class Department extends Model
    {
        /**
         * The table associated with the model.
         *
         * @var string
         */
        protected $table = 'Departments';

        /**
         * The attributes that are mass assignable.
         *
         * @var array<int, string>
         */
        protected $fillable = [
            'department_name',
            'image_path',
        ];

        /**
         * The primary key associated with the table.
         *
         * @var string
         */
        protected $primaryKey = 'departmentID';

        public $timestamps = null;

    }
