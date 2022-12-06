<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\AdminRequest;
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

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_admins,show_admins')) {
            return redirect('admin/index');
        }

        $admins = User::whereHas('roles', function($query){
            $query->where('name', 'admin');
        })

        ->when(\request()->keyword !=null, function($query){
            $query->search(\request()->keyword);
        })
        ->when(\request()->active !=null, function($query){
            $query->whereActive(\request()->active);
        })
        ->orderBy(\request()->sort_by ?? 'id' ,  \request()->order_by ?? 'desc')

        ->paginate(\request()->limit_by ?? 10);

        return view('backend.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_admins,create_admins')) {
            return redirect('admin/index');
        }

        $permissions = Permission::get(['id', 'display_name']);

        return view('backend.admins.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminRequest $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_admins,create_admins')) {
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

        $admin = User::create($input);
        $admin->markEmailAsVerified();

        $admin->attachRole(Role::whereName('admin')->first()->id);

        if(isset($request->permissions) && count($request->permissions) > 0){
            $admin->permissions()->sync($request->permissions);
        }

        Alert::success('تم اضافة أدمن بنجاح', 'Success Message');
        return redirect()->route('admin.admins.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $admin)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_admins,show_admins')) {
            return redirect('admin/index');
        }

        return view('backend.admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $admin)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_admins,update_admins')) {
            return redirect('admin/index');
        }

        $permissions = Permission::get(['id', 'display_name']);
        $adminPermissions = UserPermissions::whereUserId($admin->id)->pluck('permission_id')->toArray();

        return view('backend.admins.edit', compact('admin', 'permissions', 'adminPermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminRequest $request, User $admin)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_admins,update_admins')) {
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

            if ($admin->user_image != null && File::exists( $admin->user_image )) {
                unlink( $admin->user_image );
            }

            $filename = Str::slug($request->name).'.'.$image->getClientOriginalExtension();
            $path = ('images/user/' . $filename);
            Image::make($image->getRealPath())->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $input['user_image']  = $path;
        }

        $admin->update($input);

        if (isset($request->permissions) && count($request->permissions) > 0 ){
            $admin->permissions()->sync($request->permissions);
        }

        Alert::success('تم تعديل أدمن بنجاح', 'Success Message');
        return redirect()->route('admin.admins.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $admin)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_admins,delete_admins')) {
            return redirect('admin/index');
        }

        if ($admin->user_image != null && File::exists( $admin->user_image)) {
            unlink( $admin->user_image);
        }
        $admin->delete();

        Alert::success('تم تعديل أدمن بنجاح', 'Success Message');
        return redirect()->route('admin.admins.index');

    }



    public function removeImage(Request $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_admins,delete_admins')) {
            return redirect('admin/index');
        }

        $admin = User::whereId($request->admin_id)->first();
        if ($admin) {
            if (File::exists( $admin->user_image)) {
                unlink( $admin->user_image);

                $admin->user_image = null;
                $admin->save();
            }
        }
        return true;
    }


    public function massDestroy(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $admin = User::findorfail($id);
            if (File::exists($admin->user_image)) :
                unlink($admin->user_image);
            endif;
            $admin->delete();
        }
        return response()->json([
            'error' => false,
        ], 200);

    }

    public function changeStatus(Request $request)
    {
        $admin = User::find($request->cat_id);
        $admin->active = $request->active;
        $admin->save();
        return response()->json(['success'=>'تم تغيير حالة الأدمن بنجاح']);
    }
}
