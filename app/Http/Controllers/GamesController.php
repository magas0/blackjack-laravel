<?php

namespace App\Http\Controllers;

use App\Card;
use App\Game;
use App\Player;
use Illuminate\Http\Request;

class GamesController extends Controller
{

    // Game text message
    protected $bustText = 'BUST!';
    protected $blackjackText = 'BLACKJACK!';
    protected $youWinText = 'YOU WIN!';
    protected $youLoseText = 'YOU LOSE!';

    // Load the intro view
    public function intro(Request $request)
    {
      $request->session()->forget('deck');

      return view('intro');
    }

    // Save our game result
    public function store($result)
    {
      $game = new Game;
      $game->gamewon = $result;
      $game->save();
    }

    // Grab all info and send to the view
    public function returnView($buttonView, $messageView, $dealer, $player)
    {
      return view( 'play',
        [
          'button' => $buttonView,
          'message' => $messageView,
          'dealerCards' => $dealer->getCards(),
          'dealerScore' => $dealer->getScore(),
          'playerCards' => $player->getCards(),
          'playerScore' => $player->getScore()
        ]);
    }

    // Start the game
    public function play(Request $request) {

      // Starting new game, make sure session data is empty
      $request->session()->forget('dealer');
      $request->session()->forget('player');

      // Initialize new players
      $dealer = new Player('dealer');
      $player = new Player('player');

      $game = new Game;
      $cards = new Card;

      // Should we exit the game after 60% of cards used?
      if ($game->checkGameStop($cards->getCurrentSpot()))
      {
        $game = new Game;
        $gamesTotal = $game->count();
        $gamesWon = $game->sum('gamewon');

        return view('exit', ['gamesTotal' => $gamesTotal, 'gamesWon' => $gamesWon, 'hideNewGame' => true]);
      }

      return $this->returnView(view('buttons.deal'), '', $dealer, $player);
    }

    // You are dealing, hitting or standing
    public function playturn(Request $request) {

      if ($request->has('deal')) {
        $game = new Game;

        // Get player information
        $dealer = new Player('dealer');
        $player = new Player('player');

        // start new deck
        $cards = new Card;
        $cards->shuffleDeck();

        // deal two cards each
        $dealer->hit($cards->dealCard());
        $dealer->hit($cards->dealCard());

        $player->hit($cards->dealCard());
        $player->hit($cards->dealCard());

        // Have they won or lost?
        if ($game->checkBust($player))
        {
          $this->store(0);
          return $this->returnView('', view('message_lose', ['messageText' => $this->bustText]), $dealer, $player);
        }

        if ($game->checkBlackJack($player))
        {
          $this->store(1);
          return $this->returnView('', view('message_win', ['messageText' => $this->blackjackText]), $dealer, $player);
        }

        if ($game->checkBlackJack($dealer))
        {
          $this->store(0);
          return $this->returnView('', view('message_lose', ['messageText' => $this->youLoseText]), $dealer, $player);
        }

        // Keep going...
        return $this->returnView(view('buttons.playbuttons'), '', $dealer, $player);
      }

      if ($request->has('hit')) {
        $game = new Game;
        $cards = new Card;
        $dealer = new Player('dealer');
        $player = new Player('player');

        $player->hit($cards->dealCard());

        // Have they won or lost?
        if ($game->checkBust($player))
        {
          $this->store(0);
          return $this->returnView('', view('message_lose', ['messageText' => $this->bustText]), $dealer, $player);
        }

        if ($game->checkBlackJack($player))
        {
          $this->store(1);
          return $this->returnView('', view('message_win', ['messageText' => $this->blackjackText]), $dealer, $player);
        }

        // Keep going...
        return $this->returnView(view('buttons.playbuttons'), '', $dealer, $player);
      }

      if ($request->has('stand'))
      {
        // Get game data
        $game = new Game;
        $cards = new Card;
        $dealer = new Player('dealer');
        $player = new Player('player');

        // dealer will deal until win or lose
        $game->dealerPlay($dealer, $cards);

        // See who won
        if ($game->checkWin($player, $dealer))
        {
          $this->store(1);
          return $this->returnView('', view('message_win', ['messageText' => $this->youWinText]), $dealer, $player);
        }
        else
        {
          $this->store(0);
          return $this->returnView('', view('message_lose', ['messageText' => $this->youLoseText]), $dealer, $player);
        }
      }
    }
}
