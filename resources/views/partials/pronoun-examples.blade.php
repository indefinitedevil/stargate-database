<ul class="flex gap-4 text-xs mt-1">
    @php
        $pronounExamples = [
            __('he/him'),
            __('she/her'),
            __('they/them'),
            __('xe/xem'),
            __('ze/zir'),
            __('fae/faer'),
        ];
        shuffle($pronounExamples);
    @endphp
    @foreach ($pronounExamples as $pronouns)
        <li><a class="underline underline-offset-4 decoration-dashed cursor-pointer" onclick="populatePronouns('{{ $pronouns }}')">{{ $pronouns }}</a></li>
    @endforeach
</ul>
<script src="{{ asset('js/pronouns.js?20251019') }}" defer></script>
