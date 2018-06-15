@if($user->id !== Auth::user()->id){{--当用户访问的是自己的个人页面时，关注表单不应该被显示出来--}}
    <div id="follow_form">
        @if(Auth::user()->isFollowing($user->id))
            <form action="{{ route('follower.destroy',$user->id) }}" method="post">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button class="btn btn-sm" type="submit">取消关注</button>
            </form>
        @else
            <form action="{{ route('follower.store',$user->id) }}" method="post">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-sm btn-primary">关注</button>
            </form>
        @endif
    </div>
@endif