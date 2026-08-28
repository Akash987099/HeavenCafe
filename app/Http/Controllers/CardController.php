<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserCard;
use App\Models\CardType;

class CardController extends Controller
{
    private $cardModel;
    private $cardTypeModel;

    public function __construct()
    {
        $this->cardModel = new UserCard();
        $this->cardTypeModel = new CardType();
    }

    public function index()
    {
        $cards = $this->cardModel->with('cardType')->paginate(config('constants.pagination_limit'));
        return view('cards.index', compact('cards'));
    }
}
