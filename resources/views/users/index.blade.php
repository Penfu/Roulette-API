 <x-app-layout>
    <div class="view-container">
        @foreach ($users as $user)
            <p>{{ $user }}</p>
        @endforeach
    </div>
 </x-app-layout>
