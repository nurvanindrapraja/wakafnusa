@props(['status'])

@if ($status)
    @php
        $translatedStatus = $status;
        if ($status === 'We have emailed your password reset link.' || $status === 'passwords.sent') {
            $translatedStatus = 'Kami telah mengirimkan tautan pemulihan password ke email Anda.';
        }
    @endphp
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600']) }}>
        {{ $translatedStatus }}
    </div>
@endif
