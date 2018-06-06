@extends('layouts.default')
@section('title','重置密码')
@section('content')
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                重置密码
            </div>
            <div class="panel-body">
                @if(session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <form class="form-horizontal" method="post" action="{{ route('password.email') }}">
                    {{ csrf_field() }}
                    {{--{tip} $errors 变量被由Web中间件组提供的 Illuminate\View\Middleware\ShareErrorsFromSession 中间件绑定到视图。
                    当这个中间件被应用后，在你的视图中就可以获取到 $error 变量，可以使一直假定 $errors 变量存在并且可以安全地使用。--}}
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-md-4 control-label">邮箱地址：</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                发送密码重置邮件
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
@stop
