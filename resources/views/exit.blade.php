@extends('layouts.app')

@section('content')
  @parent

  <h1>You have won {{ $gamesWon }} of {{ $gamesTotal }} games so far.</h1>
  @include('buttons.startagain')

@endsection
