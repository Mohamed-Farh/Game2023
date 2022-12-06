<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PlayerRequest;
use App\Models\Player;
use App\Models\Role;
use App\Models\Transition;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserMaxLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_players,show_players')) {
            return redirect('admin/index');
        }

        $players = User::whereHas('roles', function($query){
            $query->where('name', 'player');
        })

            ->when(\request()->keyword !=null, function($query){
                $query->search(\request()->keyword);
            })
            ->when(\request()->account_status !=null, function($query){
                $query->whereAccountStatus(\request()->account_status);
            })
            ->orderBy(\request()->sort_by ?? 'id' ,  \request()->order_by ?? 'desc')

            ->paginate(\request()->limit_by ?? 10);

        return view('backend.players.index', compact('players'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_players,create_players')) {
            return redirect('admin/index');
        }

        return view('backend.players.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlayerRequest $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_players,create_players')) {
            return redirect('admin/index');
        }
        DB::beginTransaction();
        try {
            $input['first_name']    = $request->first_name;
            $input['last_name']     = $request->last_name;
            $input['username']      = $request->username;
            $input['email']         = $request->email;
            $input['email_verified_at']  = Carbon::now();
            $input['mobile']        = $request->mobile;
            $input['password']      = bcrypt($request->password);
            $input['account_status']        = $request->status;
            $input['mobile_verify']        = 1;

            if ($image = $request->file('user_image')) {
                $filename = Str::slug($request->username).'.'.$image->getClientOriginalExtension();
                $path = ('images/player/' . $filename);
                Image::make($image->getRealPath())->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);
                $input['user_image']  = $path;
            }

            $player = User::create($input);
            $player->markEmailAsVerified();
            $player->attachRole(Role::whereName('player')->first()->id);

            ### ِAddress
            $address['user_id']       = $player->id;
            $address['address']       = $request->address ?? '';
            $address['country_id']    = $request->country_id ?? null;
            $address['state_id']      = $request->state_id ?? null;
            $address['city_id']       = $request->city_id ?? null;
            $address['zip_code']      = $request->zip_code ?? '';
            $address['po_box']        = $request->po_box ?? '';
            UserAddress::create($address);

            DB::commit(); // insert data
            Alert::success('Player Created Successfully', 'Success Message');
            return redirect()->route('admin.players.index');

        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $player)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_players,show_players')) {
            return redirect('admin/index');
        }

        return view('backend.players.show', compact('player'));
    }
    /**
     * Show the Player Prices.
     *
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function showPrices(User $player)
    {
        //
    }
    /**
     * Show the Player Transitions.
     *
     * @param  \App\Models\User  $player
     * @return \Illuminate\Http\Response
     */
    public function showTransitions(User $player)
    {
        \Carbon\Carbon::setLocale('ar');
        $transitions = Transition::where('sender_id', $player->id)
            ->orWhere('receiver_id', $player->id)
            ->when(\request()->keyword !=null, function($query){
                $query->search(\request()->keyword);
            })
            ->orderBy(\request()->sort_by ?? 'id' ,  \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 10);

        return view('backend.transitions.index', compact('transitions', 'player'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $player)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_players,update_players')) {
            return redirect('admin/index');
        }

        $player_address = UserAddress::whereUserId($player->id)->first();
        return view('backend.players.edit', compact('player', 'player_address'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PlayerRequest $request, User $player)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_players,update_players')) {
            return redirect('admin/index');
        }
        DB::beginTransaction();
        try {
            $input['first_name']    = $request->first_name;
            $input['last_name']     = $request->last_name;
            $input['username']      = $request->username;
            $input['email']         = $request->email;
            $input['mobile']        = $request->mobile;
            $input['account_status']        = $request->account_status;

            if(trim($request->password) != ''){
                $input['password']      = bcrypt($request->password);
            }

            if ($image = $request->file('user_image')) {

                if ($player->user_image != null && File::exists( $player->user_image )) {
                    unlink( $player->user_image );
                }

                $filename = Str::slug($request->name).'.'.$image->getClientOriginalExtension();
                $path = ('images/player/' . $filename);
                Image::make($image->getRealPath())->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);
                $input['user_image']  = $path;
            }
            $player->update($input);

            ### ِAddress
            $player_address = UserAddress::whereUserId($player->id)->first();
            if(!empty($player_address)){
                $address['user_id']       = $player->id;
                $address['address']       = $request->address;
                $address['country_id']    = $request->country_id;
                $address['state_id']      = $request->state_id;
                $address['city_id']       = $request->city_id;
                $address['zip_code']      = $request->zip_code;
                $address['po_box']        = $request->po_box;
                $player_address->update($address);
            }

            DB::commit(); // insert data
            Alert::success('Player Updated Successfully', 'Success Message');
            return redirect()->route('admin.players.index');

        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $player)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_players,delete_players')) {
            return redirect('admin/index');
        }

        if ($player->user_image != null && File::exists( $player->user_image)) {
            unlink( $player->user_image);
        }
        $player->delete();

        Alert::success('Player Deleted Successfully', 'Success Message');
        return redirect()->route('admin.players.index');
    }



    public function removeImage(Request $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_players,delete_players')) {
            return redirect('admin/index');
        }

        $player = User::whereId($request->player_id)->first();
        if ($player) {
            if (File::exists( $player->user_image)) {
                unlink( $player->user_image);

                $player->user_image = null;
                $player->save();
            }
        }
        return true;
    }


    public function massDestroy(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $player = User::findorfail($id);
            if (File::exists($player->user_image)) :
                unlink($player->user_image);
            endif;
            $player->delete();
        }
        return response()->json([
            'error' => false,
        ], 200);

    }

    public function changeStatus(Request $request)
    {
        $player = User::whereId($request->cat_id)->first();
        $player->account_status = $request->status;
        $player->save();

        return response()->json(['success'=>'Status Change Successfully.']);
    }


    public function getPlayerSearch()
    {
        $players = User::whereHas('roles', function($query){
            $query->where('name', 'player');
        })
            ->when(\request()->input('query') != '', function ($query){
                $query->search(\request()->input('query'));
            })
            ->get(['id', 'first_name', 'last_name', 'email'])->toArray();

        return response()->json($players);
    }
}
