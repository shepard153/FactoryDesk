<?php

    namespace App\Models;

    use Illuminate\Support\Facades\DB;
    use Illuminate\Contracts\Auth\MustVerifyEmail;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Notifications\Notifiable;
    use Laravel\Sanctum\HasApiTokens;
    use Illuminate\Http\Request;
    use Hash;
    use Session;
    use Illuminate\Support\Facades\Auth;

    class Staff extends Authenticatable
    {
        use HasApiTokens, HasFactory, Notifiable;

        protected $primaryKey = 'staffID';
        
        /**
         * The attributes that are mass assignable.
         *
         * @var array<int, string>
         */
        protected $fillable = [
            'name',
            'email',
            'login',
            'password',
            'isAdmin',
            'department',
        ];
        
        /**
         * The attributes that should be hidden for serialization.
         *
         * @var array<int, string>
         */
        protected $hidden = [
            'password',
            'remember_token',
        ];
    }
