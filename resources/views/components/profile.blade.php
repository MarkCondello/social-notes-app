  <div class="container py-md-5 container--narrow">
    <h2>
      <img
        class="avatar-small"
        src="{{auth()->user()->avatar}}"
      /> {{$sharedData['user']->username}}
    @if(auth()->user()->username === $sharedData['user']->username)
      <a href="/edit-avatar" class="btn btn-secondary btn-sm">Edit Avatar</a>
    @endif
    @if(!$sharedData['currentlyFollowing'])
      <form
        class="ml-2 d-inline"
        action="/store-follower/{{$sharedData['user']->username}}"
        method="POST"
      >
        @csrf
        <button class="btn btn-primary btn-sm">Follow <i class="fas fa-user-plus"></i></button>
      </form>
    @else
      <form
        class="ml-2 d-inline"
        action="/delete-follower/{{$sharedData['user']->username}}"
        method="POST"
      >
        @csrf
        @method('DELETE')
        <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-times"></i></button>
      </form>
    @endif
    </h2>
    <div class="profile-nav nav nav-tabs pt-2 mb-4">
      <a
        href="/profile/{{$sharedData['user']->username}}"
        class="profile-nav-link nav-item nav-link {{$activeTab === 'profile' ? 'active' : ''}}"
      >Posts: {{$sharedData['postCount']}}</a>
      <a
        href="/profile/{{$sharedData['user']->username}}/followers"
        class="profile-nav-link nav-item nav-link {{$activeTab === 'followers' ? 'active' : ''}}"
      >Followers: {{$sharedData['followerCount']}}</a>
      <a
        href="/profile/{{$sharedData['user']->username}}/following"
        class="profile-nav-link nav-item nav-link {{$activeTab === 'following' ? 'active' : ''}}"
      >Following: {{$sharedData['followingCount']}}</a>
    </div>
    <div class="profile-slot-content">
      {{ $slot }}
    </div>
  </div>
