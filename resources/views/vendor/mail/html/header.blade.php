@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ asset('/images/sef-logo-112.png') }}" class="logo" alt="Stargate Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
