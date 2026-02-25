@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (isset($message))
<img src="{{ $message->embed(public_path('images/logo.png')) }}" class="logo" alt="{{ config('app.name', 'Quality') }} Logo" style="max-height: 75px; object-fit: contain;">
@else
<img src="{{ asset('images/logo.png') }}" class="logo" alt="{{ config('app.name', 'Quality') }} Logo" style="max-height: 75px; object-fit: contain;">
@endif
</a>
</td>
</tr>
