<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    /*使用 Laravel 提供身份验证（Auth）中间件来过滤未登录用户的 edit, update 动作。
      我们在 __construct 方法中调用了 middleware 方法，该方法接收两个参数，第一个为中间件的名称，第二个为要进行过滤的动作。
      我们通过 except 方法来设定 指定动作 不使用 Auth 中间件进行过滤，意为 —— 除了此处指定的动作以外，所有其他动作都必须登录用户才能访问，类似于黑名单的过滤机制。
      相反的还有 only 白名单方法，将只过滤指定动作。我们提倡在控制器 Auth 中间件使用中，首选 except 方法，这样的话，当你新增一个控制器方法时，默认是安全的，此为最佳实践。
    */
    public function __construct()
    {

        $this->middleware('auth',[
            'except'=>['show','create','store','index','confirmEmail']
        ]);

        /*引入中间件，注册页面只允许未登录用户访问*/
        $this->middleware('guest',[
            'only'=>'create',
        ]);


    }

    public function index()
    {
        $users=User::paginate(10);
        return view('users.index',compact('users'));
    }

    //注册用户页面
    public function  create()
    {
        return view('users.create');
    }

    //返回当前用户信息
    public function show(User $user)
    {
        /*将用户对象 $user 通过 compact 方法转化为一个关联数组，并作为第二个参数传递给 view 方法，将数据与视图进行绑定。*/
        $statuses = $user->statuses()
                         ->orderBy('created_at','desc')
                         ->paginate(15);
        return view('users.show',compact('user','statuses'));
    }

    /*返回用户信息编辑页面 Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');*/
    public function edit(User $user)
    {
        /*这里 update 是指授权类里的 update 授权方法，$user 对应传参 update 授权方法的第二个参数。
        正如上面定义 update 授权方法时候提起的，调用时，默认情况下，我们 不需要 传递第一个参数，也就是当前登录用户至该方法内，因为框架会自动加载当前登录用户。*/
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }

    /*注册用户*/
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
        /*Auth::login($user);
        session()->flash('success','欢迎，您将在这里开启一段新的旅程~');*/
        /*这里是一个『约定优于配置』的体现，此时 $user 是 User 模型对象的实例。route() 方法会自动获取 Model 的主键，也就是数据表 users 的主键 id，以上代码等同于：

        redirect()->route('users.show', [$user->id]);*/

        /*return redirect()->route('users.show',[$user]);*/

        /*注册成功后发送注册确认邮件*/
        $this->sendEmailConfirmationTo($user);
        session()->flash('success','验证邮件已发送到您注册的邮箱，请注意查收');
        return redirect('/');

    }

    /*注册成功后，发送注册确认邮件*/
    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
//        $from = 'fire@fire.com';
//        $name = 'Fire';
        $to = $user->email;
        $subject="感谢注册 Sample 应用！请确认您的邮箱";

        Mail::send($view,$data,function ($message) use ($to,$subject){
            $message->to($to)->subject($subject);
        }

        );

    }

    /*更新用户信息*/
    public function update(User $user,Request $request)
    {
        $this->validate($request,[
            'name' => 'required|max:50',
            'password' =>'nullable|confirmed|min:6'
        ]);
        $this->authorize('update',$user);
        $data=[];
        $data['name'] = $request->name;

        if($request->password){
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        session()->flash('success','个人资料更新成功');
        return redirect()->route('users.show',$user->id);

    }

    /*删除用户*/
    public function destroy(User $user)
    {
        $this->authorize('destroy',$user);
        $user->delete();
        session()->flash('success','成功删除用户');
        return back();

    }

    /*确认注册邮件*/
    public function confirmEmail($token)
    {
        $user = User::where('activation_token',$token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;

        $user->save();

        Auth::login($user);
        session()->flash('success','恭喜你，激活成功');
        return redirect()->route('users.show',[$user]);
    }




}
