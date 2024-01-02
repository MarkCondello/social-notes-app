<x-layout pageTitle="{{$sharedData['user']->username}}'s profile">
  <x-profile
    :sharedData="$sharedData"
    activeTab="profile"
  >
    @include('profile-posts-only')
  </x-profile>
</x-layout>