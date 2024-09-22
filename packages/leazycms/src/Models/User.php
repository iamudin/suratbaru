<?php
namespace Leazycms\Web\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Leazycms\FLC\Traits\Fileable;



class User extends Authenticatable
{
    use SoftDeletes,Fileable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'level',
        'status',
        'photo',
        'email',
        'user_data',
        'host',
        'unit_id',
        'url',
        'slug',
        'active_session',
        'last_login_ip',
        'last_login_at',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'username',
    ];


    protected $casts=[
        'email_verified_at' => 'datetime',
        'user_data' => 'array',

    ];

    public function posts()
    {
        return $this->hasMany(Post::class)->select((new Post)->selected);
    }
    public function getPhotoUserAttribute()
    {
        return $this->photo ? $this->photo : noimage();
    }
    public function logs()
    {
        return $this->hasMany(Log::class);
    }
    public function unit()
    {
        return $this->belongsTo(Post::class,'unit_id','id')->select('title','id','parent_id');
    }

    public function isActive(){
        return $this->status == '1';
    }
    public function isAdmin(){
        return $this->level == 'admin';
    }
    public function isAdminKantor(){
        return $this->level == 'AdminKantor';
    }
    public function isOperator(){
        return $this->level == 'operator';
    }
    public function isUser(){
        return $this->level == 'user';
    }
    public function roles(){
        return $this->hasMany(Role::class,'level','level');
    }
    public function hasRole($module,$action){
        if(!$this->isAdmin() && !$this->roles->where('module',$module)->where('action',$action)->where('level',$this->level)->count()){
         abort('403','Unauthorized action');
        }
     }
    public function get_modules(){
        return $this->roles()->where('action', 'index');
    }
}
