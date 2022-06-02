<!DOCTYPE html>
<html lang="en">
<head>
    <title>Case Study: Screening</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1" />

    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css/main.css') }}"/>
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/main.js') }}"></script>
</head>

<body>
<div class="wrapper">
    <div class="heading"><h1>{{ $study['state'] }}</h1></div>

    <div id="form-container">
        <div id="form-inner-container">
            <form>
                @foreach($formData as $row)
                    <div id="{{ $row['element_container_id'] }}" class="{{ $row['element_container_className'] }}">
                    @if ($row['usesFieldset'])
                        <fieldset>
                            <legend class="bold">{{ $row['legend'] }}</legend>
                    @endif
                        @foreach($row['fieldData'] as $field)
                            @include('partials._formfield', ['field' => $field, 'hasLegend' => $row['usesFieldset']])
                        @endforeach
                    @if ($row['usesFieldset'])
                        </fieldset>
                    @endif
                    </div>
                @endforeach

                <div>
                    <input type="hidden" id="study-id" value="{{ $study['id'] }}" />
                </div>

                <div id="feedback-message-box" class="hidden">
                    <span id="message"></span>
                </div>

                <div id="button-container">
                    <div id="submit" onclick="submit()"><span>Submit</span></div>
                </div>
            </form>
        </div>
        <div id="success-message-box" class="hidden">
            <span id="success-message"></span>
        </div>
    </div>
</div>
</body>
</html>
