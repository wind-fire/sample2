<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    //注册用户页面
    public function  create()
    {
        return view('users.create');
    }

    //返回当前用户信息
    public function show(User $user)
    {
        /*将用户对象 $user 通过 compact 方法转化为一个关联数组，并作为第二个参数传递给 view 方法，将数据与视图进行绑定。*/
        return view('users.show',compact('user'));
    }

    public function  store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'

        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),


        ]);
        /*注册后自动登录*/
        Auth::login($user);
        session()->flash('success','欢迎，您将在这里开启一段新的旅程~');
        /*这里是一个『约定优于配置』的体现，此时 $user 是 User 模型对象的实例。route() 方法会自动获取 Model 的主键，也就是数据表 users 的主键 id，以上代码等同于：

        redirect()->route('users.show', [$user->id]);*/

        return redirect()->route('users.show',[$user]);
    }


}
