<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $character->name }}</title>
    <link rel="stylesheet" href="<?php echo asset('css/boilerplate.css')?>" type="text/css">
    <link rel="stylesheet" href="<?php echo asset('css/app.css')?>" type="text/css">

</head>
<body class="">
<div id="app">
    <main id="main" class="container">
        <div class="grid">
            <p>
                <strong>Name:</strong> {{ $character->name }}<br>
                <strong>Background:</strong> {{ $character->background->name }}
            </p>
            <p>
                <strong>Body:</strong> {{ $character->body }}<br>
                <strong>Vigor:</strong> {{ $character->vigor }}
            </p>
        </div>
        <h3>Skills</h3>
        <div class="grid">
            <div>
                <h4>Background</h4>
                <ul>
                    @foreach ($character->background->skills as $skill)
                        <li>{{ $skill->name }}</li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4>Trained</h4>
                <ul>
                    @foreach ($character->displayedTrainedSkills as $characterSkill)
                        <li>{{ $characterSkill->skill->name }}</li>
                    @endforeach
                </ul>
            </div>
            @if ($character->trainingSkills->count())
                <div>
                    <h4>Training</h4>
                    <ul>
                        @foreach ($character->trainingSkills as $characterSkill)
                            <li>{{ $characterSkill->skill->name }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <h3>Feats</h3>
        <ul class="grid">
            @foreach ($character->feats as $feat)
                <li>{{ $feat->name }}</li>
            @endforeach
        </ul>
        @if (!empty($character->cards))
            <h3>Cards</h3>
            <ul class="grid">
                @foreach ($character->cards as $card)
                    <li>{{ $card->name }} ({{ $card->number }})</li>
                @endforeach
            </ul>
        @endif
    </main>
</div>
</body>
</html>