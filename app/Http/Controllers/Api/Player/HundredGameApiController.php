<?php

namespace App\Http\Controllers\Api\Player;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\HundredGameDetailsRequest;
use App\Http\Resources\HundredGameDetailsResource;
use App\Http\Resources\HundredGameResource;
use App\Http\Resources\PriceResource;
use App\Models\GamePlayer;
use App\Models\GameVote;
use App\Models\HundredGame;
use App\Models\PlayerPrice;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;


class HundredGameApiController extends Controller
{
    use GeneralTrait;

    public function currentHundredGame(Request $request)
    {
//        $hundred =  HundredGame::where('start', '<=', Carbon::now())
//                                ->where('end', '>=', Carbon::now())
//                                ->where('active', 1)
//                                ->first();
        $hundred = HundredGame::currentHundredGame()->first();
        return $this->successMessage(new HundredGameResource($hundred), 'Available Hundred Number Game');
    }

    public function hundredGameDetails(HundredGameDetailsRequest $request)
    {
        $hundred = HundredGame::whereId($request->id)->first();
        return $this->successMessage(new HundredGameDetailsResource($hundred), 'Hundred Number Game Details');
    }

    public function currentPrice(Request $request)
    {
        $currentHundredGame = HundredGame::currentHundredGame()->first();
        $currentPrice = $currentHundredGame->currentPrice() ?? $currentHundredGame->basicPrice();
        return $this->successMessage(new PriceResource($currentPrice), 'Available Price');
    }

    public function startHundredGame(Request $request)
    {
        /** Current Game & Price **/
        $currentHundredGame = HundredGame::currentHundredGame()->first();
        $currentPrice = $currentHundredGame->currentPrice();

        /** Check If Player Win This Game Before **/
        $winGameBefore = GamePlayer::where('user_id', \auth()->id())
            ->where('game_id', $currentHundredGame->id)
            ->where('game_type', 'hundred')
            ->where('win', 1)
            ->first();

        if(!$winGameBefore){
            /** Check If Game Started Before Or Not ? **/
            $openGameBefore = GamePlayer::where('user_id', \auth()->id())
                ->where('game_id', $currentHundredGame->id)
                ->where('game_type', 'hundred')
                ->where('numbers', null)
                ->where('timer', '00:00:00')
                ->where('play', 0)
                ->first();

            if(!$openGameBefore){
                //Start As Player
                $openGameNow = GamePlayer::create([
                    'user_id' => \auth()->id(),
                    'game_id' => $currentHundredGame->id,
                    'game_type' => 'hundred',
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
                ->where('game_id', $currentHundredGame->id)
                ->where('game_type', 'hundred')
                ->where('numbers', null)
                ->where('price_id', $currentPrice->id)
                ->where('vote', 0)
                ->first();
            if(!$openVoteBefore){
                $openVottingNow = GameVote::create([
                    'user_id' => \auth()->id(),
                    'game_id' => $currentHundredGame->id,
                    'game_type' => 'hundred',
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


    public function playHundredGame(Request $request)
    {

        /** جلب اللعبة الحالية و الجائزة الحالية */
        $currentHundredGame = HundredGame::currentHundredGame()->first();
        $currentPrice = $currentHundredGame->currentPrice();

        /** هل هذا اللاعب قام بالفوز بهذة اللعبة من قبل */
        $winGameBefore = GamePlayer::where('user_id', \auth()->id())
            ->where('game_id', $currentHundredGame->id)
            ->where('game_type', 'hundred')
            ->where('win', 1)
            ->first();

        $request_numbers = $request->numbers;
        $request_numbers = array_map(function($arr) {
            return intval($arr);
        }, $request_numbers);

        /** لو لم يكسب من قبل هذه اللعبة, يتم تسجيله كلاعب في هذة الجولة */
        if(!$winGameBefore){
            $playGameBefore = GamePlayer::where('user_id', \auth()->id())
                ->where('game_id', $currentHundredGame->id)
                ->where('game_type', 'hundred')
                ->where('active', 1)
                ->where('play', 0)
                ->where('win', 0)
                ->first();

            /** هل قام اللاعب بالبدء باللعبة اولا قبل عملية اختيار الارقام */
            if($playGameBefore)
            {
                $this->validate($request, [
                    'numbers' => 'required',
                    'timer' => 'required',
                ]);

                /** هل قام اللاعب بالبدء باللعبة و اختيار الارقام و لم يتعد الوقت المسموح له في اللعب */
                if( ($currentHundredGame->timer) >= ($request->timer) )
                {
                    /** هل عدد الارقام المدخلة اصبح يساوي العدد المطلوب من الارقام للفوز باللعبة */
                    if( ($currentHundredGame->no_of_win_numbers) == count($request->numbers)) {
                        //**** check if win or lose

                        if (($currentHundredGame->win_numbers) === $request_numbers) {
                            $playGameBefore->update([
                                'numbers' => $request_numbers,
                                'timer' => $request->timer,
                                'play' => 1,
                                'win' => 1
                            ]);

                            PlayerPrice::create([
                                'user_id' => \auth()->id(),
                                'game_player_id' => $playGameBefore->id,
                                'price_id' => $currentPrice->id,
                            ]);

                            return $this->SuccessMessage(1,'Congratulation, You Win The Game');

                        } else {
                            $playGameBefore->update([
                                'numbers' => $request_numbers,
                                'timer' => $request->timer,
                                'play' => 1,
                                'win' => 0
                            ]);
                            return $this->SuccessMessage(0,'You Lose The Game, Good Luck Next Time');
                        }

                    }elseif( ($currentHundredGame->no_of_win_numbers) < count($request->numbers)){
                        return $this->returnErrorMessage('You have entered a number of digits larger than required', '422');

                    }else{
                        /** يتم ادخال الارقام و جلب العمليات السابقة لهذا اللاعب لإلغاء اختيار الارقام الخاطئة مرة أخري */
                        $previousPlayerNumbers = GamePlayer::where('user_id', \auth()->id())
                            ->where('game_id', $currentHundredGame->id)
                            ->where('game_type', 'hundred')
                            ->where('numbers', '!=', null)
                            ->where('active', 1)
                            ->where('play', 1)
                            ->where('win', 0)
                            ->pluck('numbers')->toArray();

                        /** هل هناك محاولات سابقة للعب في هذة اللعبة */
                        if($previousPlayerNumbers){

                            $samePreviousNumbers = [];
                            $disabledPreviousNumbers = [];
                            foreach ($previousPlayerNumbers as $previousPlayerNumber){
                                //TODO:******* جلب الترتيب الصحيح
                                $keys = array_keys($previousPlayerNumber, $request_numbers[array_keys($request_numbers)[0]]);
                                foreach($keys as $key) {
                                    if(array_slice($previousPlayerNumber, $key, count($request_numbers)) == $request_numbers){
                                        if( !in_array($previousPlayerNumber,$samePreviousNumbers))
                                            array_push($samePreviousNumbers, $previousPlayerNumber);

                                        $key_for_next_number =count($request_numbers);
                                        if( isset($previousPlayerNumber[$key_for_next_number]) && !in_array($previousPlayerNumber[$key_for_next_number],$disabledPreviousNumbers))
                                            array_push($disabledPreviousNumbers, $previousPlayerNumber[$key_for_next_number]);
                                    }
                                }
                                //TODO:*******
                            }
                            $data = [
                                'disabled_previous_choosen_numbers' =>  $disabledPreviousNumbers,
                            ];
                            return $this->successMessage($data, 'The wrong numbers chosen in previous attempts');
                        }else{
                            /** هذة اول محاولة للعب في هذة اللعبة */
                            $playGameBefore->update([
                                'numbers' => $request_numbers,
                            ]);
                            $data = [
                                'disabled_previous_choosen_numbers' =>  [],
                            ];
                            return $this->successMessage($data, 'First Try, The wrong numbers chosen in previous attempts');
                        }
                    }
                }elseif( ($currentHundredGame->timer) < ($request->timer) ){
                    $playGameBefore->update([
                        'numbers' => null,
                        'timer' => $request->timer,
                        'play' => 1,
                        'win' => 0
                    ]);
                    return $this->SuccessMessage(0,'You took a long time, You lost the game, Good luck next time');
                }
            }else{
                return $this->returnErrorMessage('You Must Start Game First, Before Choosing Numbers');
            }

        }elseif($winGameBefore){
            /**  لو كسب من قبل هذه اللعبة, يتم تسجيله كرأي جَمهور في هذة الجولة */
            $voteGameBefore = GameVote::where('user_id', \auth()->id())
                ->where('game_id', $currentHundredGame->id)
                ->where('game_type', 'hundred')
                ->where('vote', 0)
                ->where('active', 1)
                ->first();

            if($voteGameBefore)
            {
                $this->validate($request, [
                    'numbers' => 'required',
                ]);

                if( ($currentHundredGame->no_of_win_numbers) == count($request->numbers))
                {
                    $voteGameBefore->update([
                        'numbers' => $request_numbers,
                        'vote' => 1
                    ]);
                    return $this->returnSuccessMessage('Successfully Adding Your Voting');
                }else{
                    return $this->returnErrorMessage('يرجي ادخال العدد الصحيح من الارقام', '422');
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
        $currentHundredGame = HundredGame::currentHundredGame()->first();

        /**  هل هذا اللاعب قام بالفوز بهذة اللعبة من قبل */
        $winGameBefore = GamePlayer::where('user_id', \auth()->id())
            ->where('game_id', $currentHundredGame->id)
            ->where('game_type', 'hundred')
            ->where('win', 1)
            ->first();

        /** لو لم يكسب من قبل هذه اللعبة, يتم تسجيله كلاعب في هذة الجولة */
        if(!$winGameBefore){
            $playGameBefore = GamePlayer::where('user_id', \auth()->id())
                ->where('game_id', $currentHundredGame->id)
                ->where('game_type', 'hundred')
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

                $previousPlayerVotesNumbers = GameVote::where('game_id', $currentHundredGame->id)
                    ->where('game_type', 'hundred')
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
                return $this->returnSuccessMessage('You Must Start Game First, Before Choosing Numbers');
            }

        }elseif($winGameBefore){
            //**** لو كسب من قبل هذه اللعبة, يتم تسجيله كرأي جَمهور
            return $this->returnSuccessMessage('Public Opinion cannot be shown because you are already one of them');
        }
    }


}
