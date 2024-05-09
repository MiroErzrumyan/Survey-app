<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
    <style>
    </style>
</head>
<body>
<div class="container">
    <div class="question">
        @if($question)
            <h2>{{"Test $groupId"}}</h2>

            <p>{{ $question->text }}</p>
            <form method="post" action="{{ route('answer.create') }}" class="answer-form">
                <input type="hidden" name="group_id" value="{{ $groupId }}">
                <input type="hidden" name="decreased_times" value="0" id="decreased_times">
                <input type="hidden" name="time_remaining" id="timeRemaining"
                       value="{{ ($question->time_to_move_point && $question->moveable_point_percent) ? $question->time_to_move_point: null}}">
                @csrf
                @foreach ($question->answers as $answer)
                    <label>
                        <input type="radio" name="answer_id" value="{{ $answer->id }}" required>
                        {{ $answer->text }}
                    </label><br>
                @endforeach
                <p id="get-point-text">
                    {{"For right answer you will get " . $question->max_point . " points"}}
                </p>
                @if($question->time_to_move_point && $question->moveable_point_percent)

                    <p id="text">
                        {{"in every $question->time_to_move_point  seconds your score will decrease with $question->moveable_point_percent percent"}}
                    </p>
                    <p id="timer-text">
                        Decreased 0/{{ floor(100 / $question->moveable_point_percent) }} times
                    </p>
                    <p id="timer">Decreased seconds {{ $question->time_to_move_point}}</p>
                @else
                    <p>
                        You have time to think free because time is not decreased
                    </p>
                @endif
                <p>{{$availablePoints}} of 100 Points</p>
                <button type="submit" id="submitButton">Submit Answer</button>
            </form>
        @else
            <form method="post" action="{{ route('group.start-over',$groupId) }}" class="answer-form">
                @csrf
                @method('delete')
                <p>You got {{$availablePoints}} of 100 Points</p>
                <button>Start answers</button>
            </form>
        @endif
    </div>
</div>
<script>

    @if($question)
    let percent = {{$question->moveable_point_percent}};

    let timeToMovePoint = {{$question->time_to_move_point}};

    let maxPoint = {{$question->max_point}};

    let minPoint = {{$question->min_score}};

    let decreaseMaxTimes = Math.floor(100 / percent)

    let decreasedTimes = 0

    let loggedTimes = 0

    const timerElement = document.getElementById('timer');

    const timerText = document.getElementById('timer-text');

    const getPointTag = document.getElementById('get-point-text');

    const timeRemainingInput = document.getElementById('timeRemaining');

    const decreasedInput = document.getElementById('decreased_times');

    const durationDecrease = {{ $question->time_to_move_point }};

    const submitButton = document.getElementById('submitButton');

    const countdown = setInterval(function () {
        let timeRemaining = parseInt(timeRemainingInput.value);
        loggedTimes++


        if (durationDecrease === loggedTimes) {
            decreasedTimes++
            loggedTimes = 0
            timerText.innerText = `Decreased ${decreasedTimes + '/' + decreaseMaxTimes} times`
            decreasedInput.value = decreasedTimes
            let point = decreasedTimes === decreaseMaxTimes ? minPoint : maxPoint - (maxPoint * (decreasedTimes * percent) / 100)
            getPointTag.innerText = `For right answer you will get ${point} point`
            timeRemaining = decreasedTimes !== decreaseMaxTimes ? timeToMovePoint : 0

        }

        if (timeRemaining > 0) {
            timeRemaining--;

            timeRemainingInput.value = timeRemaining;

            timerElement.textContent = `Decreased seconds ${timeRemaining}`;

        } else {
            clearInterval(countdown);
            submitButton.click();
        }
    }, 1000);
    @endif
</script>
</body>
</html>
