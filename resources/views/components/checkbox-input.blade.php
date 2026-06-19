@props(['disabled' => false, 'checked' => false])

<input type="checkbox" @disabled($disabled) @checked($checked) {{ $attributes->merge(['class' => '']) }}>
