<?php
namespace Leazycms\Web\Http\Controllers;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Leazycms\Web\Models\Role;
use Leazycms\Web\Models\User;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array {
        return [
            new Middleware('auth'),
            function (Request $request, Closure $next) {
                if(!$request->user()->isAdmin() && in_array($request->segment(2),['user','role'])){
                    return redirect()->route('panel.dashboard')->send()->with('danger','Akses hanya admin');
                }
                return $next($request);
            },
        ];

    }
    public function index()
    {
        return view('cms::backend.users.index');
    }
    public function account(Request $request){
        $user = $request->user();
        if($request->isMethod('post')){
            $data= $request->validate([
                'photo'=> 'nullable|file|mimetypes:image/jpeg,image/png',
                'name'=> 'required|string',
                'username'=>'required|string|regex:/^[a-zA-Z\p{P}]+$/u|'.Rule::unique('users')->ignore($user->id),
                'email'=> 'required|string|regex:/^[a-zA-Z\p{P}]+$/u|'.Rule::unique('users')->ignore($user->id),
                'password'=>'nullable|string|confirmed|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]+$/',
            ]);
            $data['photo'] = $user->photo;
            $request['media'] =  $user->photo;

            if($request->hasFile('photo')){
                $data['photo'] = $user->addFile(['file'=>$request->file('photo'),'purpose'=>'author_photo','mime_type'=>['image/png','image/jpeg']]);
            }
            if($pass = $request->password){
                $data['password'] = bcrypt($pass);
            }else{
                $data['password'] = $user->password;

            }

            User::findOrFail($user->id)->update($data);
            return back()->with('success','Berhasil perbarui Akun');
        }
        return view('cms::backend.users.account',
        ['user'=>$user]);

     }
    public function datatable(Request $request)
    {
        $data = User::with('unit.parent')->withCount('posts')->where('level','!=','admin');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group">';
                $btn .= '<a target="_blank" href="' .url($row->url).'"  class="btn btn-info btn-sm fa fa-globe"></a>';
                $btn .= '<a href="' . route('user.edit', $row->id).'"  class="btn btn-warning btn-sm fa fa-edit"></a>';
                $btn .= $row->posts->count() ==0 ? '<button onclick="deleteAlert(\'' . route('user.destroy', $row->id).'\')" class="btn btn-danger btn-sm fa fa-trash"></button>':'';
                $btn .= '</div>';
                return $btn;
            })
            ->addColumn('name', function ($row) {
                return '<span class="text-primary">'.$row->name.'</span><br><small class="text-muted">'.$row->email.'</small>';
            })
            ->addColumn('role', function ($row) {
                return str($row->level)->headline();
            })
            ->addColumn('unit', function ($row) {
                if($row->unit->parent){
                    $unit = $row->unit->title .' | '.$row->unit->parent->title;
                }else{
                    $unit = $row->unit->title;

                }
                return $unit;
            })
            ->addColumn('username', function ($row) {
                return $row->username;
            })
            ->addColumn('status', function ($row) {
                $active = '<span class="badge bg-success text-white">Aktif</span>';
                $nonactive = '<span class="badge bg-danger text-white">Diblokir</span>';
                return $row->status=='active' ? $active : $nonactive;
            })
            ->addColumn('photo', function ($row) {
                return '<img src="'.$row->photo_user.'" height="50" class="rounded">';
            })
            ->rawColumns(['action','unit','name','status','photo','role','username'])
            ->toJson();
}
public function create(){
    return view('cms::backend.users.form',['role'=>null,'user'=>null]);
}
public function store(Request $request){
    $data = $request->validate([
        'name'=>'required|string|regex:/^[a-zA-Z\s\p{P}]+$/u',
        'foto'=> 'nullable|mimetypes:image/jpeg,image/png',
        'unit_id'=> 'required',
        'email'=>'required|email|'.Rule::unique('users'),
        'username'=>'required|string|regex:/^[a-zA-Z\p{P}]+$/u|'.Rule::unique('users'),
        'status'=>'required|string|in:active,blocked',
        'level'=>'required|string|in:'.get_option('roles'),
        'password'=>'required|string|confirmed|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]+$/',
    ]);
    $data['slug'] = $slug = str($request->name)->slug();
    $data['url'] = 'author/'.$slug;
    $data['level'] = $request->level;
    $data['password'] = bcrypt($request->password);
    $data['host'] = $request->getHost();
    $data = User::create($data);
    if($request->hasFile('photo')){
        $data->update(['photo'=>$data->addFile(['file'=>$request->file('photo'),'purpose'=>'author_photo','mime_type'=>['image/png','image/jpeg']])]);
    }
    return to_route('user.edit',$data->id)->with('success','User berhasil ditambah');
}
public function edit(User $user){
    return view('cms::backend.users.form',['user'=>$user]);
}
public function update(Request $request, User $user){
    $data = $request->validate([
        'name'=>'required|string|regex:/^[a-zA-Z\s\p{P}]+$/u',
        'foto'=> 'nullable|mimetypes:image/jpeg,image/png',
        'unit_id'=> 'required',
        'email'=>'required|email|'.Rule::unique('users')->ignore($user->id),
        'username'=>'required|string|regex:/^[a-zA-Z\p{P}]+$/u|'.Rule::unique('users')->ignore($user->id),
        'status'=>'required|string|in:active,blocked',
        'level'=>'required|string|in:'.get_option('roles'),
        'password'=>'nullable|string|confirmed|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]+$/',
    ]);
    $data['slug'] = $slug = str($request->name)->slug();
    $data['url'] = 'author/'.$slug;
    $data['host'] = $request->getHost();
    $data['password'] = $user->password;
    if($request->password){
    $data['password'] = bcrypt($request->password);
    }
    $request['media'] = $user->photo;
    $user->update($data);
    if($request->hasFile('photo')){
        $user->update(['photo'=>$user->addFile(['file'=>$request->file('photo'),'purpose'=>'author_photo','mime_type'=>['image/png','image/jpeg']])]);
    }
    return back()->with('success','User  berhasil diupdate');
}
public function destroy(User $user){
    $user->delete();
}

public function roleIndex(){
    abort_if(!get_option('roles'),404);
    $role = Role::get();
    return view('cms::backend.users.role',compact('role'));
}
public function roleUpdate(Request $request){
    $role = array();
    foreach($request->except('_token') as $key =>$r){
        $data = explode('_',$key);
        foreach($data as $n=>$r){
        if($n==0){
            $k['level'] = $r;
        }elseif($n==1){
            $k['module'] = $r;

        }else{
            $k['action'] = $r;

        }
        }
        array_push($role,$k);
    }
    Role::whereNotNull('id')->delete();
    foreach($role as $r){
        Role::create($r);
    }
        return back()->with('success','Hak akses diperbarui');
}
}

