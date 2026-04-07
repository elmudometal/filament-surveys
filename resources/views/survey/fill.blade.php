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

<section class="position-relative pt-0">
    <div class="container">
        <form method="POST" action="{{ route('survey.submit', [$survey, $model_id]) }}" class="row">
            @csrf
            <div class="mb-4">
                <h2>{{ $survey->title }}</h2>
                @if ($survey->description)
                    <p class="lead">{!! $survey->description !!}</p>
                @endif
            </div>

            @foreach ($survey->questions->groupBy('question_section') as $section_questions)
                <h3 class="mb-4">Sección {{ $loop->iteration }}. {{ $section_questions->first()->question_section }}</h3>
                @foreach ($section_questions as $question)
                    <div class="mb-3 question question-{{ $question->question_type }}">
                        <h4 class="mb-3">{{ $question->question_text }}</h4>
                        @if ($question->question_type === 'free_text')
                            <div class="form-group">
                                @foreach ($question->options as $option)
                                    <input type="hidden" name="question_{{ $question->id }}[]" value="{{ $option->id }}" @required ($question->is_required) />
                                    <textarea class="form-control" name="question_{{ $question->id }}_justify[{{ $option->id }}]" rows="3" @required ($question->is_required)></textarea>
                                @endforeach
                            </div>
                        @else
                            <div class="form-check">
                                @foreach ($question->options as $option)
                                    <label class="form-check-label">
                                        <input
                                                class="form-check-input {{ $option->option_justify ? 'option-justify' : '' }}"
                                                type="{{ $question->question_type === 'multiple_choice' ? 'checkbox' : 'radio'}}"
                                                name="question_{{ $question->id }}[]"
                                                value="{{ $option->id }}"
                                                @required ($question->is_required)
                                        />
                                        {{ $option->option_text }} </label
                                    ><br />
                                @endforeach
                            </div>
                            <textarea
                                    placeholder="Por favor, cuéntanos por qué."
                                    class="form-control mt-2"
                                    style="display: none"
                                    name="question_{{ $question->id }}_justify[{{ $question->id }}]"
                                    rows="3"
                            ></textarea>
                        @endif

                        @error ('question_' . $question->id)
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
            @endforeach
            <div class="mb-3">
                <button type="submit" class="btn btn-primary btn-lg">Enviar mi opinión</button>
            </div>
        </form>
    </div>
</section>
</body>
</html>
