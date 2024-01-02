<div class="list-group">
  @foreach($posts as $post)
  <x-post-preview :post="$post"/>
  @endforeach
</div>