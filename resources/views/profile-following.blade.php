<x-layout pageTitle="Who {{$sharedData['user']->username}} follows">
  <x-profile
    :sharedData="$sharedData"
    activeTab="following"
  >
  @include('profile-following-only')
  </x-profile>
</x-layout>