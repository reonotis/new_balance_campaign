<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-white text-center text-3xl font-bold leading-tight">
        </h2>
    </x-slot>

    <div class="pt-4 pb-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-2 mb-2"><a href="{{ route('admin.try_on') }}" >TRY ON キャンペーン のリストを確認する</a></div>
            </div>
        </div>
    </div>

</x-admin-layout>
