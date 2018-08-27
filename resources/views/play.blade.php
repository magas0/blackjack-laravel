@extends('layouts.app')

@section('content')
  @parent

  <p>
    @if ($dealerScore > 0)
      Dealer Score: {{ $dealerScore }}
    @endif
  </p>
  <h2>
    @foreach ($dealerCards as $card)
      [
      {{ $card[1] }}
      @switch($card[0])
          @case('club')
            <span>&#9827;</span>
            @break
          @case('spade')
            <span>&#9824;</span>
            @break
          @case('heart')
            <span class="text-danger">&#9829;</span>
            @break
          @case('diamond')
            <span class="text-danger">&#9830;</span>
            @break
      @endswitch
      ]
    @endforeach
  </h2>

  <br />

  <p>
    @if ($playerScore > 0)
      Player Score: {{ $playerScore }}
    @endif
  </p>
  <h2>
    @foreach ($playerCards as $card)
      [
      {{ $card[1] }}
      @switch($card[0])
          @case('club')
            <span>&#9827;</span>
            @break
          @case('spade')
            <span>&#9824;</span>
            @break
          @case('heart')
            <span class="text-danger">&#9829;</span>
            @break
          @case('diamond')
            <span class="text-danger">&#9830;</span>
            @break
      @endswitch
      ]
    @endforeach
  </h2>

  {!! $button !!}
  {!! $message !!}

@endsection
