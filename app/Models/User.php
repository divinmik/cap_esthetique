<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\User;
use App\Models\ModuleCap;
use Illuminate\Support\Str;
use App\Models\Helper_function;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'firstname',
        'lastname',
        'birthdate',
        'birthplace',
        'address',
        'phone',
        'email',
        'city',
        'level',
        'contact_salon',
        'program',
        'message',
        'type_inscription',
        'type_formation',
        'profile_photo_path',
        'is_valide',
        'email_verified_at',
        'password',
        'is_actif',
        'statut',
        'user_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function modules()
    {
        return $this->hasMany(ModuleCap::class);
    }

    public function UniqueCode($statut)
    {
        if($statut == "professeur"){
            $mat = "P";
        }
        
        $next  = 1;

        do {
            $numero = str_pad($next, 3, '0', STR_PAD_LEFT);
            $exists = User::where('code', $mat.'-'.$numero)
            ->where('statut',$statut)
            ->exists();

            if ($exists) {
                $next++;
            } else {
                break;
            }
        } while (true);
        
        return $mat.'-'.$numero;
    }

    public static function SaveData($donne,$statut){
        //add code
        $code = new self();

        if ($donne['profile_photo_path']) {
            $name = 'profilprof_'.Str::random(12).'.'.$donne['profile_photo_path']->getClientOriginalExtension();
            $signaturePath = $donne['profile_photo_path']->storeAs('profils', $name, 'public');
        } 
        
        $donne['code'] = $code->UniqueCode($statut);
        $donne['firstname'] = ucwords(strtolower($donne['firstname']));
        $donne['lastname'] = ucwords(strtolower($donne['lastname']));
        $donne['phone'] = Helper_function::phone($donne['phone']);
        $donne['statut'] = "professeur";
        $donne['profile_photo_path'] = $signaturePath;
        //add user create
        $pwd = "Prof#".$donne['code'];
        $pwd_crypt = bcrypt($pwd);
        $donne['user_id'] = auth()->user()->id;
        $donne['password'] = $pwd_crypt;
        
        $donne = User::create($donne);
        
        return [
            'password'=>$pwd,
            'data'=>$donne,
            'message'=>"Enrégistrement réussi",
            'statut'=>200
        ];
    }

    public static function EditData($id,$donne,$user){
        
        if ($donne['profile_photo_path']) {
            // supprimer l'ancienne si elle existe
            if (!empty($donne['profile_photo_path']) && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $stored = $donne['profile_photo_path']->store('profils', 'public'); // => profils/xxxx.jpg
            $donne['profile_photo_path'] = $stored;
           
        }
        //unset path if null
        if(!$donne['profile_photo_path'])unset($donne['profile_photo_path']);
        

        $donne['firstname'] = ucwords(strtolower($donne['firstname']));
        $donne['lastname'] = ucwords(strtolower($donne['lastname']));
        $donne['phone'] = Helper_function::phone($donne['phone']);
        $donne['is_actif'] = (int)$donne['is_actif'];
        
       
        User::where('id',$id)->update($donne);
        return [
            'message'=>"modifier",
            'statut'=>200
        ];
    }

    public static function DeleteData($id,$donne){
        
        User::where('id',$id)->delete($donne);
        return [
            'message'=>"supprimer",
            'statut'=>200
        ];
    }
}
