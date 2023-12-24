<x-layout pageTitle="Editing {{$post->title}}">
  <div class="container py-md-5 container--narrow">
    <form action="/posts/{{$post->id}}/update" method="POST">
      <p><small><strong>
        <a href="/posts/{{$post->id}}">&laquo; Back to post</a> 
      </strong></small></p>
      @csrf
      @method('PUT')
      <div class="form-group">
        <label for="post-title" class="text-muted mb-1"><small>Title</small></label>
        <input
          required
          name="title"
          id="post-title"
          class="form-control form-control-lg form-control-title"
          type="text"
          placeholder=""
          autocomplete="off"
          value="{{old('title', $post->title)}}"
        />
        @error('title')
          <div class="alert alert-danger m-0 small">{{ $message }}</div>
          @enderror
        </div>
        <div class="form-group">
          <label for="post-body" class="text-muted mb-1"><small>Body Content</small></label>
          <textarea
            required
            name="body"
            id="post-body"
            class="body-content
            tall-textarea form-control"
            type="text"
          >{{old('body', $post->body)}}</textarea>
          @error('body')
          <div class="alert alert-danger m-0 small">{{ $message }}</div>
          @enderror
      </div>
      <button class="btn btn-primary">Save Changes</button>
    </form>
  </div>

</x-layout>