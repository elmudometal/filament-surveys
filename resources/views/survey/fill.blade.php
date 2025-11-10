<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $survey->title }}</title>
    <style>
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif; margin: 2rem; }
        .error { color: #b91c1c; }
        .question { margin-bottom: 1rem; }
        button { background: #111827; color: #fff; padding: .5rem 1rem; border: 0; border-radius: .25rem; }
    </style>
</head>
<body>
<h1>{{ $survey->title }}</h1>
@if($survey->description)
    <p>{!! $survey->description !!}</p>
@endif

<form method="POST" action="{{ route('survey.submit', $participant->unique_link) }}">
    @csrf

    @foreach($survey->questions as $question)
        <div class="question">
            <label><strong>{{ $question->question_text }}</strong></label>

            @if($question->question_type === 'single_choice')
                <div>
                    @foreach($question->options as $option)
                        <label>
                            <input type="radio" name="question_{{ $question->id }}[]" value="{{ $option->id }}">
                            {{ $option->option_text }}
                        </label><br>
                    @endforeach
                </div>
            @else
                <div>
                    @foreach($question->options as $option)
                        <label>
                            <input type="checkbox" name="question_{{ $question->id }}[]" value="{{ $option->id }}">
                            {{ $option->option_text }}
                        </label><br>
                    @endforeach
                </div>
            @endif

            @error('question_'.$question->id)
            <div class="error">{{ $message }}</div>
            @enderror
        </div>
    @endforeach

    <button type="submit">Enviar</button>
</form>
</body>
</html>
