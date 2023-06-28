<x-layout>
    <div class="container py-md-5 container--narrow">
      <h2>
        <img class="avatar-small" src="{{$sharedData['user']->avatar}}" /> {{$sharedData['user']->username}}
        @auth
        @if(!$sharedData['doesFollow'] && auth()->user()->username!=$sharedData['user']->username)
          <form class="ml-2 d-inline" action="/follow/{{$sharedData['user']->id}}" method="POST">@csrf
            <button class="btn btn-primary btn-sm">Follow <i class="fas fa-user-plus"></i></button>

          </form>
          @elseif($sharedData['doesFollow'] && auth()->user()->username!=$sharedData['user']->username)
          <form class="ml-2 d-inline" action="/follow/{{$sharedData['user']->id}}" method="POST">@csrf @method('DELETE')
            <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-times"></i></button>
          </form>
          @else
          <a href="/manage-avatar" class="btn btn-secondary btn-sm">Manage Avatar</a>
          @endif
        </h2>
      @endauth
  
        <div class="profile-nav nav nav-tabs pt-2 mb-4">
          <a href="/profile/{{$sharedData['user']->username}}" class="profile-nav-link nav-item nav-link {{$active=="posts"?"active":""}}">Posts: {{$sharedData['posts_count']}}</a>
          <a href="/profile/{{$sharedData['user']->username}}/followers" class="profile-nav-link nav-item nav-link {{$active=="followers"?"active":""}}">Followers: {{$sharedData['followers_count']}}</a>
          <a href="/profile/{{$sharedData['user']->username}}/following" class="profile-nav-link nav-item nav-link {{$active=="following"?"active":""}}">Following: {{$sharedData['following_count']}}</a>
        </div>
  
        <div class="slot">
            {{ $slot }}
        </div>
      </div>
</x-layout>