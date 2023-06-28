<x-layout>
<div class="container py-md-5 container--narrow">
    @if($posts->isEmpty())
    <div class="text-center">
      <h2>Hello <strong>{{auth()->user()->username}}</strong>, your feed is empty.</h2>
      <p class="lead text-muted">Your feed displays the latest posts from the people you follow. If you don&rsquo;t have any friends to follow that&rsquo;s okay; you can use the &ldquo;Search&rdquo; feature in the top menu bar to find content written by people with similar interests and then follow them.</p>
    </div>
    @else
    <div class="container py-md-5 container--narrow">
        <h2 class="text-center mb-4">The Latest From Those You Follow</h2>
        <div class="list-group">
            @foreach ($posts as $post)
          <a href="/posts/{{$post->id}}" class="list-group-item list-group-item-action">
            <img class="avatar-tiny" src="{{$post->user->avatar}}" />
            <strong>{{$post->title}}</strong>
            <span class="text-muted small">by {{$post->user->username}} on {{$post->created_at}}</span>
          </a>
          @endforeach
        </div>
      </div>
      {{-- displat links --}}
      <div class="mt-0">

          {{$posts->links()}}
        </div>
    @endif
  </div>

</x-layout>