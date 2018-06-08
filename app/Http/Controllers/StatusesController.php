<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusesController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*发布微博*/
    public function store(Request $request)
    {
        $this->validate($request,[
            'content' => 'required|max:140'
        ]);

        Auth::user()->statuses()->create([
            'content' =>$request['content']
        ]);

        return redirect()->back();
    }

    /*删除微博*/
    public function destroy(Status $status)
    {
        /*删除微博时，判断是否有授权，没有授权抛出 403 异常*/
        $this->authorize('destroy',$status);
        $status->delete();
        session()->flash('success','微博删除成功');
        return redirect()->back();
    }
}
