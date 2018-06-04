<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    /*引入中间件，登录页面只允许未登录用户访问*/
    public function __construct()
    {
        $this->middleware('guest',[
            'only'=>'create',
        ]);
    }

    /*返回登录视图*/
    public function create()
    {
        return view('sessions.create');
    }

    /*获取用户登录信息*/
    public function store(Request $request)
    {

        /*validator 由 App\Http\Controllers\Controller 类中的 ValidatesRequests 进行定义，
        因此我们可以在所有的控制器中使用 validate 方法来进行数据验证。
        validate 方法接收两个参数，第一个参数为用户的输入数据，第二个参数为该输入数据的验证规则。*/
        $credentials = $this->validate($request,[
            'email' => 'required|email|max:255',
            'password' => 'required'

        ]);


        /*attempt 方法会接收一个数组来作为第一个参数，该参数提供的值将用于寻找数据库中的用户数据。因此在上面的例子中，attempt 方法执行的代码逻辑如下：

        使用 email 字段的值在数据库中查找；
        如果用户被找到：
        1). 先将传参的 password 值进行哈希加密，然后与数据库中 password 字段中已加密的密码进行匹配；
        2). 如果匹配后两个值完全一致，会创建一个『会话』给通过认证的用户。会话在创建的同时，也会种下一个名为 laravel_session 的 HTTP Cookie，
            以此 Cookie 来记录用户登录状态，最终返回 true；
        3). 如果匹配后两个值不一致，则返回 false；
        如果用户未找到，则返回 false。
        结合 attempt 方法对用户身份进行认证的具体代码实现如下，使用 Auth 前需要对其进行引用。*/

        /*在 Laravel 的默认配置中，如果用户登录后没有使用『记住我』功能，则登录状态默认只会被记住两个小时。
        如果使用了『记住我』功能，则登录状态会被延长到五年。我们可以通过使用 Laravel 提供的『记住我』功能来保存一个记忆令牌，
        用于长时间记录用户登录的状态。而 Laravel 已默认为用户生成的迁移文件中已经包含了 remember_token 字段，该字段将用于保存『记住我』令牌。
        Auth::attempt() 方法可接收两个参数，第一个参数为需要进行用户身份认证的数组，第二个参数为是否为用户开启『记住我』功能的布尔值。
        接下来让我们修改会话控制器中的 store 方法，为 Auth::attempt() 添加『记住我』参数。*/

        if(Auth::attempt($credentials,$request->has('remember')))
        {
            session()->flash('success','欢迎回来');
//            return redirect()->route('users.show',[Auth::user()]);
            return redirect()->intended(route('users.show',[Auth::user()]));

        }else{
            session()->flash('danger','很抱歉，您的邮箱和密码不匹配');
            return redirect()->back();
        }


    }

    /*用户注销*/
    public function destroy()
    {
        Auth::logout();
        session()->flash('success','您已成功退出');
        return redirect()->route('login');
    }



}
