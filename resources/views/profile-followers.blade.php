<x-layout pageTitle="{{$sharedData['user']->username}}'s followers">
  <x-profile
    :sharedData="$sharedData"
    activeTab="followers"
  >
    @include('profile-followers-only')
  </x-profile>
</x-layout>