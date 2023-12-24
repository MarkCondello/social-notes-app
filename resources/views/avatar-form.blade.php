<x-layout pageTitle="Update avatar">
  <div class="container container--narrow py-md-5">
    <h2 class="text-center mb03">Upload a new Avatar</h2>
    <form action="/upload-avatar" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="mb-3">
        <input type="file" name="avatar" required>
        @error('avatar')
        <p class="alert small alert-danger shadow-sm">{{$message}}</p>
        @enderror
      </div>
      <button class="btn btn-primary">Save</button>
    </form>
  </div>
</x-layout>
