@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<span style="font-size: 24px; font-weight: 800; color: #006400; font-family: 'Outfit', 'Inter', sans-serif; letter-spacing: -0.5px;">Wakaf Nusa</span>
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
