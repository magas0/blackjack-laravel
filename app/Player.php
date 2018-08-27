<?php

namespace App;

use Session;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    // Set up player variables
    protected $name = '';
    protected $currentCards = [];
    protected $currentScore = 0;

    public function __construct($name)
    {
      $this->name = $name;

      // Does this player already exist?
      if (Session::has($name))
      {
        $this->currentCards = session($name)['currentCards'];
        $this->currentScore = session($name)['currentScore'];
      }

      // If they don't exist, save it in the session
      else
      {
        session([$name =>
                [
                    'currentCards' => $this->currentCards,
                    'currentScore' => $this->currentScore
                ]
        ]);
      }
    }

    // Add a card to your current hand
    public function hit($card)
    {
      $this->currentCards[] = $card;

      // add to score, Ace is worth 11 points
      $cardValue = 0;
      if ($card[1] == 'Ace')
      {
        $cardValue = 11;
      }
      else
      {
        // Is it a number? if not, use 10 for J,Q,K
        $cardValue = is_numeric($card[1]) ? (int)$card[1] : 10;
      }

      $this->currentScore = $this->currentScore + $cardValue;

      // save to session
      session([$this->name =>
              [
                'currentCards' => $this->currentCards,
                'currentScore' => $this->currentScore
              ]
      ]);
    }

    // Grab the players current hand
    public function getCards()
    {
      return session($this->name)['currentCards'];
    }

    // Get the players current score for thier current hand
    public function getScore()
    {
      return session($this->name)['currentScore'];
    }

    // Get all the players stats
    public function getAllStats()
    {
      return session($this->name);
    }


}
