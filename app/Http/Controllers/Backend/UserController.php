<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\UserRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserPermissions;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_users,show_users')) {
            return redirect('admin/index');
        }

        $users = User::whereHas('roles', function($query){
            $query->where('name', 'user');
        })

        ->when(\request()->keyword !=null, function($query){
            $query->search(\request()->keyword);
        })
        ->when(\request()->active !=null, function($query){
            $query->whereActive(\request()->active);
        })
        ->orderBy(\request()->sort_by ?? 'id' ,  \request()->order_by ?? 'desc')

        ->paginate(\request()->limit_by ?? 10);

        return view('backend.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_users,create_users')) {
            return redirect('admin/index');
        }

        $permissions = Permission::get(['id', 'display_name']);

        return view('backend.users.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_users,create_users')) {
            return redirect('admin/index');
        }

        $input['first_name']    = $request->first_name;
        $input['last_name']     = $request->last_name;
        $input['username']      = $request->username;
        $input['email']         = $request->email;
        $input['email_verified_at']  = Carbon::now();
        $input['mobile']        = $request->mobile;
        $input['password']      = bcrypt($request->password);
        $input['active']        = $request->active;

        if ($image = $request->file('user_image')) {
            $filename = Str::slug($request->username).'.'.$image->getClientOriginalExtension();
            $path = ('images/user/' . $filename);
            Image::make($image->getRealPath())->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $input['user_image']  = $path;
        }

        $user = User::create($input);
        $user->markEmailAsVerified();

        $user->attachRole(Role::whereName('user')->first()->id);

        if(isset($request->permissions) && count($request->permissions) > 0){
            $user->permissions()->sync($request->permissions);
        }

        Alert::success('تم اضافة مستخدم بنجاح', 'Success Message');
        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_users,display_users')) {
            return redirect('admin/index');
        }

        return view('backend.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_users,update_users')) {
            return redirect('admin/index');
        }

        $permissions = Permission::get(['id', 'display_name']);
        $userPermissions = UserPermissions::whereUserId($user->id)->pluck('permission_id')->toArray();


        return view('backend.users.edit', compact('user', 'permissions', 'userPermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_users,update_users')) {
            return redirect('admin/index');
        }

        $input['first_name']    = $request->first_name;
        $input['last_name']     = $request->last_name;
        $input['username']      = $request->username;
        $input['email']         = $request->email;
        $input['mobile']        = $request->mobile;
        $input['active']        = $request->active;

        if(trim($request->password) != ''){
            $input['password']      = bcrypt($request->password);
        }

        if ($image = $request->file('user_image')) {

            if ($user->user_image != null && File::exists( $user->user_image )) {
                unlink( $user->user_image );
            }

            $filename = Str::slug($request->name).'.'.$image->getClientOriginalExtension();
            $path = ('images/user/' . $filename);
            Image::make($image->getRealPath())->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $input['user_image']  = $path;
        }

        $user->update($input);

        if (isset($request->permissions) && count($request->permissions) > 0 ){
            $user->permissions()->sync($request->permissions);
        }

        Alert::success('تم تعديل مستخدم بنجاح', 'Success Message');
        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_users,delete_users')) {
            return redirect('admin/index');
        }

        if ($user->user_image != null && File::exists( $user->user_image)) {
            unlink( $user->user_image);
        }
        $user->delete();

        Alert::success('تم حذف مستخدم بنجاح', 'Success Message');
        return redirect()->route('admin.users.index');

    }



    public function removeImage(Request $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_users,delete_users')) {
            return redirect('admin/index');
        }

        $user = User::whereId($request->user_id)->first();
        if ($user) {
            if (File::exists( $user->user_image)) {
                unlink( $user->user_image);

                $user->user_image = null;
                $user->save();
            }
        }
        return true;
    }



    public function massDestroy(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $user = User::findorfail($id);
            if (File::exists($user->user_image)) :
                unlink($user->user_image);
            endif;
            $user->delete();
        }
        return response()->json([
            'error' => false,
        ], 200);

    }

    public function changeStatus(Request $request)
    {
        $user = User::find($request->cat_id);
        $user->active = $request->active;
        $user->save();
        return response()->json(['success'=>'تم تعديل حالة مستخدم بنجاح']);
    }
}
