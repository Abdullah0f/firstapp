<x-profile :sharedData="$sharedData" :active="$active">

    <div class="list-group">
        @foreach($follows as $follow)
        <a href="/profile/{{$follow->follower->username}}" class="list-group-item list-group-item-action">
            <img class="avatar-tiny" src="{{$follow->follower->avatar}}" />
            {{$follow->follower->username}}
        </a>
        @endforeach
    </div>
</x-profile>
