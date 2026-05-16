<x-dashboard-layout>
    @section('title', 'Profil Saya')
    @section('page-title', 'Profil Saya')

    @section('sidebar-nav')
        @include('dashboard.' . strtolower(auth()->user()->role) . '.partials.nav')
    @endsection

    <div class="space-y-6">
        <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-dashboard-layout>
