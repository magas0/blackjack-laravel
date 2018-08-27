<?php

namespace App;

use Session;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    // Card suits and values
    const SUITS = ['spade', 'club', 'heart', 'diamond'];
    const VALUES = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];

    // Hold our 52 cards
    protected $deckOfCards;

    // Where are we in the deck?
    protected $currentSpot;

    public function __construct() {

      // If the deck is already in the session, use it
      if (Session::has('deck'))
      {
        $this->deckOfCards = session('deck')['cards'];
        $this->currentSpot = session('deck')['currentSpot'];
      }

      // Otherwise create a new deck and save it to the session
      else
      {
        $this->deckOfCards = Arr::crossJoin(Card::SUITS, Card::VALUES);
        $this->currentSpot = 0;

        session(['deck' =>
                [
                  'cards' => $this->deckOfCards,
                  'currentSpot' => $this->currentSpot
                ]
        ]);
      }

    }

    // Grab our deck of cards
    public function getDeck() {
      return $this->deckOfCards;
    }

    public function getCurrentSpot() {
      return $this->currentSpot;
    }

    // Shuffle the deck of cards
    public function shuffleDeck() {
      $shuffled_cards = array();
      $keys = array_keys($this->deckOfCards);
      shuffle($keys);

      foreach ($keys as $key) {
        $shuffled_cards[] = $this->deckOfCards[$key];
      }

      $this->deckOfCards = $shuffled_cards;
    }

    // Deal one card and save the new spot in the session
    public function dealCard()
    {
      $currentSpot = $this->currentSpot;
      $this->currentSpot++;

      session(['deck' =>
              [
                'cards' => $this->deckOfCards,
                'currentSpot' => $this->currentSpot
              ]
      ]);

      return $this->deckOfCards[$currentSpot];
    }
}
