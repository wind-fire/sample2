@extends('layouts.default')
@section('title',$user->name)
@section('content')
   <div class="row">
       <div class="col-md-offset-2 col-md-8">
           <div class="col-md-12">
               <div class="col-md-offset-2 col-md-8">
                   <section class="user_info">
                       {{--通过给 @include 方法传参，将用户数据以关联数组的形式传送到 _user_info 局部视图上--}}
                       @include('shared._user_info', ['user' => $user])
                   </section>
               </div>
           </div>
           <div class="col-md-12">
               {{--在个人页面视图中，我们使用了 count($statuses) 方法来判断当前页面是否存在微博动态，
               如果不存在则不对微博的局部视图和分页链接进行渲染。现在视图已经构建完成，让我们针对用户微博的显示进行样式优化。--}}
               @if (count($statuses) > 0)
                   <ol class="statuses">
                       @foreach ($statuses as $status)
                           @include('statuses._status')
                       @endforeach
                   </ol>
                   {!! $statuses->render() !!}
               @endif
           </div>
       </div>
   </div>
@stop
