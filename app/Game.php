<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    // Dealer rules
    protected $blackJack = 21;
    protected $dealerHit = 16;
    protected $dealerStand = 17;

    // Stop when we have used 32 cards
    protected $gameStop = 32;

    // Check if we need to stop the game after using 60% of the cards
    public function checkGameStop($currentSpot) {
      if ($currentSpot >= $this->gameStop) {
        return true;
      }
      else
      {
        return false;
      }
    }

    // Check if the player has gone bust
    public function checkBust($player)
    {
      if ($player->getScore() > $this->blackJack)
      {
        return true;
      }

      else
      {
        return false;
      }
    }

    // Check if the player got blackjack
    public function checkBlackJack($player)
    {
      if ($player->getScore() == $this->blackJack)
      {
        return true;
      }

      else
      {
        return false;
      }
    }

    // Compare against the dealer and see who won
    public function checkWin($player, $dealer)
    {
      // Did the dealer go bust?
      if ($this->checkBust($dealer))
      {
        return true;
      }

      // Do you have a higher score than the dealer?
      if ($player->getScore() >= $dealer->getScore())
      {
        return true;
      }

      else
      {
        return false;
      }
    }

    // Let the dealer run through the cards after 'stand'
    public function dealerPlay($dealer, $cards)
    {
      while ($dealer->getScore() <= $this->dealerHit)
      {
        $dealer->hit($cards->dealCard());

        if ($dealer->getScore() >= $this->dealerStand)
        {
          break;
        }
      }
    }
}
