<?php

namespace App\Http\Controllers\Api\Player;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoseNumberGameDetailsRequest;
use App\Http\Resources\LoseNumberGameDetailsResource;
use App\Http\Resources\LoseNumberGameResource;
use App\Http\Resources\PriceResource;
use App\Models\GamePlayer;
use App\Models\GameVote;
use App\Models\LoseNumberGame;
use App\Models\PlayerPrice;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;


class LoseNumberGameApiController extends Controller
{
    use GeneralTrait;


    public function currentLoseNumberGame(Request $request)
    {
        $loseNumber = LoseNumberGame::currentLoseNumberGame()->first();
        return $this->successMessage(new LoseNumberGameResource($loseNumber), 'Available Lose Number Game');
    }

    public function loseNumberGameDetails(LoseNumberGameDetailsRequest $request)
    {
        $loseNumber = LoseNumberGame::whereId($request->id)->first();
        return $this->successMessage(new LoseNumberGameDetailsResource($loseNumber), 'Lose Number Game Details');
    }

    public function currentPrice(Request $request)
    {
        $currentLoseNumberGame = LoseNumberGame::currentLoseNumberGame()->first();
        $currentPrice = $currentLoseNumberGame->currentPrice() ?? $currentLoseNumberGame->basicPrice();
        return $this->successMessage(new PriceResource($currentPrice), 'Available Price');
    }

    public function startLoseNumberGame(Request $request)
    {
        /** Current Game & Price **/
        $currentLoseNumberGame = LoseNumberGame::currentLoseNumberGame()->first();
        $currentPrice = $currentLoseNumberGame->currentPrice();

        /** Check If Player Win This Game Before **/
        $winGameBefore = GamePlayer::where('user_id', \auth()->id())
            ->where('game_id', $currentLoseNumberGame->id)
            ->where('game_type', 'loseNumber')
            ->where('win', 1)
            ->first();

        if(!$winGameBefore){
            /** Check If Game Started Before Or Not ? **/
            $openGameBefore = GamePlayer::where('user_id', \auth()->id())
                ->where('game_id', $currentLoseNumberGame->id)
                ->where('game_type', 'loseNumber')
                ->where('numbers', null)
                ->where('timer', '00:00:00')
                ->where('play', 0)
                ->first();

            if(!$openGameBefore){
                //Start As Player
                $openGameNow = GamePlayer::create([
                    'user_id' => \auth()->id(),
                    'game_id' => $currentLoseNumberGame->id,
                    'game_type' => 'loseNumber',
                    'price_id' => $currentPrice->id,
                    'timer' => '00:00:00',
                    'numbers' => null,
                ]);
                $data = [ 'started_game_id' => $openGameNow->id ];
                return $this->successMessage($data,'The Game Started Successfully');
            }else{
                $data = [ 'started_game_id' => $openGameBefore->id ];
                return $this->successMessage($data, 'You Have Already Started This Game Before');
            }

        }elseif($winGameBefore){
            //Start As Vote
            $openVoteBefore = GameVote::where('user_id', \auth()->id())
                ->where('game_id', $currentLoseNumberGame->id)
                ->where('game_type', 'loseNumber')
                ->where('numbers', null)
                ->where('price_id', $currentPrice->id)
                ->where('vote', 0)
                ->first();
            if(!$openVoteBefore){
                $openVottingNow = GameVote::create([
                    'user_id' => \auth()->id(),
                    'game_id' => $currentLoseNumberGame->id,
                    'game_type' => 'loseNumber',
                    'price_id' => $currentPrice->id,
                    'numbers' => null,
                ]);
                $data = [ 'voting_game_id' => $openVottingNow->id ];
                return $this->successMessage($data,'The Voting Started Successfully');
            }else{
                $data = [ 'voting_game_id' => $openVoteBefore->id ];
                return $this->successMessage($data, 'You Have Already Started This Voting Before');
            }
        }
    }


    public function playLoseNumberGame(Request $request)
    {

        /** جلب اللعبة الحالية و الجائزة الحالية */
        $currentLoseNumberGame = LoseNumberGame::currentLoseNumberGame()->first();
        $currentPrice = $currentLoseNumberGame->currentPrice();

        /** هل هذا اللاعب قام بالفوز بهذة اللعبة من قبل */
        $winGameBefore = GamePlayer::where('user_id', \auth()->id())
            ->where('game_id', $currentLoseNumberGame->id)
            ->where('game_type', 'loseNumber')
            ->where('win', 1)
            ->first();

        $request_numbers = $request->numbers;
        $request_numbers = array_map(function ($arr) {
            return intval($arr);
        }, $request_numbers);

        /** لو لم يكسب من قبل هذه اللعبة, يتم تسجيله كلاعب في هذة الجولة */
        if (!$winGameBefore) {
            $playGameBefore = GamePlayer::where('user_id', \auth()->id())
                ->where('game_id', $currentLoseNumberGame->id)
                ->where('game_type', 'loseNumber')
                ->where('active', 1)
                ->where('play', 0)
                ->where('win', 0)
                ->first();

            /** هل قام اللاعب بالبدء باللعبة اولا قبل عملية اختيار الارقام */
            if ($playGameBefore) {
                $this->validate($request, [
                    'numbers' => 'required',
                    'timer' => 'required',
                ]);

                /** هل قام اللاعب بالبدء باللعبة و اختيار الارقام و لم يتعدى الوقت المسموح له في اللعب */
                if (($currentLoseNumberGame->timer) >= ($request->timer)) {
                    /** هل الرقم الخاسر من ضمن الارقام */
                    if (!in_array($currentLoseNumberGame->lose_number, $request->numbers)) {

                        /** هل عدد الارقام المدخلة اصبح يساوي العدد المطلوب من الارقام للفوز باللعبة */
                        if (count($request->numbers) == 8) {
                            $playGameBefore->update([
                                'numbers' => $request_numbers,
                                'timer' => $request->timer,
                                'play' => 1,
                                'win' => 1
                            ]);
                            /** ربط اللاعب باللعبة التي فاز بها */
                            PlayerPrice::create([
                                'user_id' => \auth()->id(),
                                'game_player_id' => $playGameBefore->id,
                                'price_id' => $currentPrice->id,
                            ]);
                            /** زيادة التوكن للاعب الفائز */
                            if ($currentPrice->win_tokens != null || $currentPrice->win_tokens != 0) {
                                $updatedPlayer = User::whereId(\auth()->id())->first();
                                $updatedPlayer->update(['token_amount' => $updatedPlayer->token_amount + $currentPrice->win_tokens]);
                            }
                            return $this->successMessage(1,'Congratulation, You Win The Game');

                        } elseif (count($request->numbers) > 8) {
                            /** لقد قمت بادخال اكثر من 8 ارقام */
                            return $this->returnErrorMessage('Sorry, You entered more than 8 numbers', '422');

                        } else {
                            /** لقد قمت بادخال أقل من 8 ارقام */
                            $playGameBefore->update([
                                'numbers' => $request_numbers,
                            ]);
                            return $this->successMessage(2,'Good job, move on, You close to winning');
                        }
                    } else {
                        $playGameBefore->update([
                            'numbers' => $request_numbers,
                            'timer' => $request->timer,
                            'play' => 1,
                            'win' => 0
                        ]);
                        return $this->successMessage(0, 'You Lose The Game, Good Luck Next Time');
                    }
                } elseif (($currentLoseNumberGame->timer) < ($request->timer)) {
                    $playGameBefore->update([
                        'numbers' => null,
                        'timer' => $request->timer,
                        'play' => 1,
                        'win' => 0
                    ]);
                    return $this->successMessage( 0,'You took a long time, You lost the game, Good luck next time');
                }

            }else{
                return $this->returnErrorMessage('You Must Start Game First, Before Choosing Numbers', '422');
            }


        }elseif($winGameBefore){
            /**  لو كسب من قبل هذه اللعبة, يتم تسجيله كرأي جَمهور في هذة الجولة */
            $voteGameBefore = GameVote::where('user_id', \auth()->id())
                ->where('game_id', $currentLoseNumberGame->id)
                ->where('game_type', 'loseNumber')
                ->where('vote', 0)
                ->where('active', 1)
                ->first();

            if($voteGameBefore)
            {
                $this->validate($request, [
                    'numbers' => 'required',
                ]);

                /** هل الرقم الخاسر من ضمن الارقام */
                if (!in_array($currentLoseNumberGame->lose_number, $request->numbers)) {
                    /** هل عدد الارقام المدخلة اصبح يساوي العدد المطلوب من الارقام للفوز باللعبة */
                    if (count($request->numbers) == 8) {
                        $voteGameBefore->update([
                            'numbers' => $request_numbers,
                            'vote' => 1
                        ]);
                        return $this->successMessage(1, 'Your Voting Added Successfully');

                    } elseif (count($request->numbers) > 8) {
                        /** لقد قمت بادخال اكثر من 8 ارقام */
                        return $this->returnErrorMessage('Sorry, You entered more than 8 numbers', '422');

                    } else {
                        /** لقد قمت بادخال أقل من 8 ارقام */
                        $voteGameBefore->update([
                            'numbers' => $request_numbers,
                        ]);
                        return $this->successMessage(2,'Good job, move on, You close to winning');
                    }
                } else {
                    $voteGameBefore->update([
                        'numbers' => $request_numbers,
                        'vote' => 1
                    ]);
                    return $this->successMessage(0,'You Lose The Game, Good Luck Next Time');
                }

            }else{
                return $this->returnErrorMessage('You Must Start Game First, Before Choosing Numbers', '422');
            }
        }
    }

    //
    public function getVoting(Request $request)
    {
        /**  جلب اللعبة الحالية */
        $currentLoseNumberGame = LoseNumberGame::currentLoseNumberGame()->first();

        /**  هل هذا اللاعب قام بالفوز بهذة اللعبة من قبل */
        $winGameBefore = GamePlayer::where('user_id', \auth()->id())
            ->where('game_id', $currentLoseNumberGame->id)
            ->where('game_type', 'loseNumber')
            ->where('win', 1)
            ->first();

        /** لو لم يكسب من قبل هذه اللعبة, يتم تسجيله كلاعب في هذة الجولة */
        if(!$winGameBefore){
            $playGameBefore = GamePlayer::where('user_id', \auth()->id())
                ->where('game_id', $currentLoseNumberGame->id)
                ->where('game_type', 'loseNumber')
                ->where('numbers', null)
                ->where('active', 1)
                ->where('play', 0)
                ->where('win', 0)
                ->first();

            /** هل قام اللاعب بالبدء باللعبة اولا قبل عملية اختيار الارقام */
            if($playGameBefore)
            {
                $this->validate($request, [
                    'numbers' => 'required',
                ]);

                /** يتم ادخال الارقام و جلب العمليات السابقة لهذا اللاعب لإلغاء اختيار الارقام الخاطئة مرة أخري */
                $request_numbers = $request->numbers;
                $request_numbers = array_map(function($arr) {
                    return intval($arr);
                }, $request_numbers);

                $previousPlayerVotesNumbers = GameVote::where('game_id', $currentLoseNumberGame->id)
                    ->where('game_type', 'loseNumber')
                    ->where('numbers', '!=', null)
                    ->where('active', 1)
                    ->where('vote', 1)
                    ->pluck('numbers')->toArray();

                /** هل هناك آراء جمهور لهدة اللعبة */
                if($previousPlayerVotesNumbers)
                {
                    $samePreviousNumbers = [];
                    $playersVoteNumbers = [];
                    foreach ($previousPlayerVotesNumbers as $previousPlayerVotesNumber){
                        //TODO:******* جلب الترتيب الصحيح
                        $keys = array_keys($previousPlayerVotesNumber, $request_numbers[array_keys($request_numbers)[0]]);
                        foreach($keys as $key) {
                            if(array_slice($previousPlayerVotesNumber, $key, count($request_numbers)) == $request_numbers){
                                if( !in_array($previousPlayerVotesNumber,$samePreviousNumbers))
                                    array_push($samePreviousNumbers, $previousPlayerVotesNumber);

                                $key_for_next_number =count($request_numbers);
//                                if( isset($previousPlayerVotesNumber[$key_for_next_number]) && !in_array($previousPlayerVotesNumber[$key_for_next_number],$playersVoteNumbers))
                                array_push($playersVoteNumbers, $previousPlayerVotesNumber[$key_for_next_number]);
                            }
                        }
                        //TODO:*******
                    }

                    $roundVoteNumbers = array_count_values( $playersVoteNumbers );
                    arsort($roundVoteNumbers);
                    $result = array_keys($roundVoteNumbers);
                    $data = [
                        'expected_numbers' =>  $result,
                    ];

                    return $this->successMessage($data, 'Public Opinion');
                }else{
                    return $this->returnSuccessMessage('Public Opinion has not yet been added to this game');
                }
            }else{
                return $this->returnErrorMessage('You Must Start Game First, Before Choosing Numbers', '422');
            }

        }elseif($winGameBefore){
            //**** لو كسب من قبل هذه اللعبة, يتم تسجيله كرأي جَمهور
            return $this->returnSuccessMessage('Public Opinion cannot be shown because you are already one of them');
        }
    }

}
