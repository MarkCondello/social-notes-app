@foreach($followers as $follower)
<div class="list-group">
  <a href="/profile/{{$follower->userFollowing->username}}" class="list-group-item list-group-item-action">
    <img class="avatar-tiny" src="{{$follower->userFollowing->avatar}}"/>
    <p>{{$follower->userFollowing->username}}</p>
   </a>
</div>
@endforeach