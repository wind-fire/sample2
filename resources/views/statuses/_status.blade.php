<li id="status-{{ $status->id }}">
    <a href="{{ route('users.show', $user->id )}}">
        <img src="{{ $user->gravatar() }}" alt="{{ $user->name }}" class="gravatar"/>
    </a>
    <span class="user">
    <a href="{{ route('users.show', $user->id )}}">{{ $user->name }}</a>
  </span>
    <span class="timestamp">
        {{--该方法的作用是将日期进行友好化处理--}}
    {{ $status->created_at->diffForHumans() }}
  </span>
    <span class="content">{{ $status->content }}</span>
    {{--接下来我们要在用户发布过的每一条微博旁边加上一个删除按钮，因此需要把删除按钮加到渲染单条微博的局部视图上。
    并且删除按钮必须是微博的作者本人才能看到，我们可以很方便的利用 Laravel 授权策略提供的 @can Blade 命令，在 Blade 模板中做授权判断--}}
    @can('destroy',$status)
        <form action="{{ route('statuses.destroy',$status->id) }}" method="post">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button type="submit" class="btn btn-sm btn-danger status-delete-btn">删除</button>
        </form>
    @endcan
</li>