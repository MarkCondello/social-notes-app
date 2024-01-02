@foreach($following as $follower)
<div class="list-group">
  <a href="/profile/{{$follower->userFollower->username}}" class="list-group-item list-group-item-action">
    <img class="avatar-tiny" src="{{$follower->userFollower->avatar}}"/>
    <p>{{$follower->userFollower->username}}</p>
  </a>
</div>
@endforeach