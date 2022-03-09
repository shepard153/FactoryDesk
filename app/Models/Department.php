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

        /**
         * 
         */
        function createDepartment(string $departmentName, string $imagePath = 'default')
        {
            if ($imagePath == 'default') {
                $this->imagePath = sprintf('%sagent.png', IMAGES_PATH);
            }
            else{
                $this->imagePath = $imagePath;
            }

            $this->departmentName = $departmentName;

            if (isset($this->departmentName)){
                DB::table("Departments")->insert(["department_name" => $this->departmentName,
                                                    "image_path" => $this->imagePath]);
            }
            else{
                echo "Nie podano nazwy działu";
            }
        }

        function updateDepartment(int $deparmentID, array $updateParams)
        {
            $this->departmentID = $departmentID;
            DB::table("Departments")->update($updateParams)->where('departmentID', '=', $this->deparmentID);
        }

        function deleteDepartment(int $departmentID)
        {
            $this->departmentID = $departmentID;
            DB::table("Departments")->delete()->where('departmentID', '=', $this->departmentID);
        }
    }
