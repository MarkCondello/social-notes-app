<x-layout pageTitle="{{$sharedData['user']->username}}'s profile">
  <x-profile
    :sharedData="$sharedData"
    activeTab="profile"
  >
    <div class="list-group">
      @foreach($posts as $post)
      <x-post-preview :post="$post"/>
      @endforeach
    </div>
  </x-profile>
</x-layout>