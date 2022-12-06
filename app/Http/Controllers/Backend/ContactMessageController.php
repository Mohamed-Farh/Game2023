<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;


class ContactMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contactUs_messages,show_contactUs_messages')) {
            return redirect('admin/index');
        }

        $contactMessages = ContactMessage::when(\request()->keyword !=null, function($query){
            $query->search(\request()->keyword);
        })
        ->when(\request()->status !=null, function($query){
            $query->whereStatus(\request()->status);
        })
        ->orderBy(\request()->sort_by ?? 'id' ,  \request()->order_by ?? 'desc')

        ->paginate(\request()->limit_by ?? 10);

        return view('backend.contact-messages.index', compact('contactMessages'));
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactMessage $contactMessage)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contactUs_messages,show_contactUs_messages')) {
            return redirect('admin/index');
        }

        $contactMessage->delete();
        Alert::success('Contact Messages Deleted Successfully', 'Success Message');
        return redirect()->route('admin.contact-messages.index');
    }

    public function contactUsMessagesCount()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contactUs_messages,show_contactUs_messages')) {
            return redirect('admin/index');
        }

        return 0;

        $data = ContactMessage::whereStatus(1)->count();
        return response()->json($data);
    }


    public function massDestroy(Request $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contactUs_messages,show_contactUs_messages')) {
            return redirect('admin/index');
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            $message = ContactMessage::findorfail($id);
            $message->delete();
        }
        return response()->json([
            'error' => false,
        ], 200);

    }

    public function changeStatus(Request $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contactUs_messages,show_contactUs_messages')) {
            return redirect('admin/index');
        }
        $message = ContactMessage::find($request->cat_id);
        $message->status = $request->status;
        $message->save();
        return response()->json(['success'=>'Status Change Successfully.']);
    }

}

