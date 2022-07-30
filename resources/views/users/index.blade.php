<x-app-layout>
    <div class="view-container">
        <x-table>
            <x-slot name="head">
                <x-table.heading class="text-left">
                    Name
                </x-table.heading>
                <x-table.heading class="text-left">
                    Email
                </x-table.heading>
                <x-table.heading class="text-left">
                    Balance
                </x-table.heading>
            </x-slot>
            <x-slot name="body">
                @foreach ($users as $user)
                    <x-table.row>
                        <x-table.cell>
                            {{ $user->name }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $user->email }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $user->balance }}
                        </x-table.cell>
                    </x-table.row>
                @endforeach
            </x-slot>
        </x-table>
    </div>
</x-app-layout>
