@if ($post)
<a href="/posts/{{$post->id}}" class="list-group-item list-group-item-action">
  <img class="avatar-tiny" src="{{$post->user->avatar}}"/>
  <strong>{{$post->title}}</strong>&nbsp;<span class="text-muted small">
    @isset ($includeWrittenBy)
    written by {{$post->user->username}} 
    @endisset
    on {{$post->created_at->format('n/j/Y')}}</span>
</a>
@endif