<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class FollowersController extends Controller
{
    //
    public function  __construct()
    {
//        中间件过滤未认证用户
        $this->middleware('auth');
    }

    public function  store(User $user)
    {
        if(Auth::user()->id === $user->id) {
            redirect('/');
        }

        if(!Auth::user()->isFollowing($user->id)){

            Auth::user()->follow($user->id);
        }

        return redirect()->route('users.show',$user->id);


    }

    /*由于这两个动作都需要用户登录之后才能进行操作，因此我们为这两个动作都加上请求过滤。由于用户不能对自己进行关注和取消关注，
    因此我们在 store 和 destroy 方法中都对用户身份做了判断，当执行关注和取消关注的用户对应的是当前的用户时，重定向到首页。*/
    public function  destroy(User $user)
    {

        if (Auth::user()->id === $user->id){
            redirect('/');
        }

        if (Auth::user()->isFollowing($user->id)){

            Auth::user()->unfollow($user->id);
        }

        return redirect()->route('users.show',$user->id);



    }





}
