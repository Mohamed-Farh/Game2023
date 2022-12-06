<?php

namespace App\Http\Controllers\Api\Player;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StartHundredGameRequest;
use App\Http\Resources\HundredGameResource;
use App\Http\Resources\PriceResource;
use App\Models\GamePlayer;
use App\Models\GameVote;
use App\Models\HundredGame;
use App\Models\PlayerPrice;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
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
        return $this->successMessage(new HundredGameResource($hundred), 'اللعبة المتاحة حاليا');
    }

    public function currentPrice(Request $request)
    {
        $currentHundredGame = HundredGame::currentHundredGame()->first();
        $currentPrice = $currentHundredGame->currentPrice() ?? $currentHundredGame->basicPrice();
        return $this->successMessage(new PriceResource($currentPrice), 'الجائزة المتاحة حاليا');
    }

    public function startHundredGame(StartHundredGameRequest $request)
    {
        //Current Game & Price
        $currentHundredGame = HundredGame::currentHundredGame()->first();
        $currentPrice = $currentHundredGame->currentPrice();


        //Check If Player Win This Game Before
        $winGameBefore = GamePlayer::where('user_id', \auth()->id())
            ->where('game_id', $currentHundredGame->id)
            ->where('game_type', 'hundred')
//            ->where('numbers', $currentHundredGame->win_numbers )
            ->where('win', 1)
            ->first();

        if(!$winGameBefore){
            $openGameBefore = GamePlayer::where('user_id', \auth()->id())
                ->where('game_id', $currentHundredGame->id)
                ->where('game_type', 'hundred')
                ->where('numbers', null)
                ->where('timer', '00:00:00')
                ->where('play', 0)
                ->first();
            if(!$openGameBefore){
                //Start As Player
                GamePlayer::create([
                    'user_id' => \auth()->id(),
                    'game_id' => $currentHundredGame->id,
                    'game_type' => 'hundred',
                    'price_id' => $currentPrice->id,
                    'timer' => '00:00:00',
                    'numbers' => null,
                ]);
            }
            return $this->returnSuccessMessage('تم بدأ اللعبة بنجاح');

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
                $game = GameVote::create([
                    'user_id' => \auth()->id(),
                    'game_id' => $currentHundredGame->id,
                    'game_type' => 'hundred',
                    'price_id' => $currentPrice->id,
                    'numbers' => null,
                ]);
            }
            return $this->returnSuccessMessage('تم بدأ اضافة رأي بنجاح');
        }
    }


    public function playHundredGame(Request $request)
    {

        //**** جلب اللعبة الحالية و الجائزة الحالية
        $currentHundredGame = HundredGame::currentHundredGame()->first();
        $currentPrice = $currentHundredGame->currentPrice();

        //**** هل هذا اللاعب قام بالفوز بهذة اللعبة من قبل
        $winGameBefore = GamePlayer::where('user_id', \auth()->id())
            ->where('game_id', $currentHundredGame->id)
            ->where('game_type', 'hundred')
//            ->where('numbers', $currentHundredGame->win_numbers)
            ->where('win', 1)
            ->first();

        $request_numbers = $request->numbers;
        $request_numbers = array_map(function($arr) {
            return intval($arr);
        }, $request_numbers);

        //**** لو لم يكسب من قبل هذه اللعبة, يتم تسجيله كلاعب في هذة الجولة
        if(!$winGameBefore){
            $playGameBefore = GamePlayer::where('user_id', \auth()->id())
                ->where('game_id', $currentHundredGame->id)
                ->where('game_type', 'hundred')
//                ->where('numbers', null)
                ->where('active', 1)
                ->where('play', 0)
                ->where('win', 0)
                ->first();

            //**** هل قام اللاعب بالبدء باللعبة اولا قبل عملية اختيار الارقام
            if($playGameBefore)
            {
                $this->validate($request, [
                    'numbers' => 'required',
                     'timer' => 'required',
                ]);

                //**** هل قام اللاعب بالبدء باللعبة و اختيار الارقام و لم يتعد الوقت المسموح له في اللعب
                if( ($currentHundredGame->timer) >= ($request->timer) )
                {
                    //**** هل عدد الارقام المدخلة اصبح يساوي العدد المطلوب من الارقام للفوز باللعبة
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

                            return $this->returnSuccessMessage('مبروك , لقد فزت باللعبة');

                        } else {
                            $playGameBefore->update([
                                'numbers' => $request_numbers,
                                'timer' => $request->timer,
                                'play' => 1,
                                'win' => 0
                            ]);
                            return $this->returnSuccessMessage('حظ سعيد المرة القادمة , لقد خسرت باللعبة');
                        }

                    }elseif( ($currentHundredGame->no_of_win_numbers) < count($request->numbers)){
                        return $this->returnErrorMessage('لقد قمت بإدخال عدد من الارقام أكبر من المطلوب', '422');

                    }else{
                        //**** يتم ادخال الارقام و جلب العمليات السابقة لهذا اللاعب لإلغاء اختيار الارقام الخاطئة مرة أخري
                        $previousPlayerNumbers = GamePlayer::where('user_id', \auth()->id())
                            ->where('game_id', $currentHundredGame->id)
                            ->where('game_type', 'hundred')
                            ->where('numbers', '!=', null)
                            ->where('active', 1)
                            ->where('play', 1)
                            ->where('win', 0)
                            ->pluck('numbers')->toArray();

                        //****** هل هناك محاولات سابقة للعب في هذة اللعبة
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
                            return $this->successMessage($data, 'الارقام الخاطئة التي اختارها اللاعب سابقا');
                        }else{
                            //****** هذة اول محاولة للعب في هذة اللعبة
                            $playGameBefore->update([
                                'numbers' => $request_numbers,
                            ]);
                            return $this->returnSuccessMessage('استمر في اختيار الارقام حتي تصل للحد المطلوب');
                        }
                    }
                }elseif( ($currentHundredGame->timer) < ($request->timer) ){
                    $playGameBefore->update([
                        'numbers' => null,
                        'timer' => $request->timer,
                        'play' => 1,
                        'win' => 0
                    ]);
                    return $this->returnSuccessMessage('لقد اخذت وقت طويل , حظ سعيد المرة القادمة , لقد خسرت باللعبة');
                }
            }else{
                return $this->returnSuccessMessage('يرجي البدء باللعبة أولا قبل اختيار الارقام');
            }

        }elseif($winGameBefore){
            //**** لو كسب من قبل هذه اللعبة, يتم تسجيله كرأي جَمهور في هذة الجولة
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
                    return $this->returnSuccessMessage('تم اضافة رأيك بنجاح');
                }else{
                    return $this->returnErrorMessage('يرجي ادخال العدد الصحيح من الارقام', '422');
                }

            }else{
                return $this->returnSuccessMessage('يرجي ألبدء باللعبة أولا قبل اختيار الارقام');
            }
        }
    }

    //
    public function getVoting(Request $request)
    {
        //**** جلب اللعبة الحالية
        $currentHundredGame = HundredGame::currentHundredGame()->first();

        //**** هل هذا اللاعب قام بالفوز بهذة اللعبة من قبل
        $winGameBefore = GamePlayer::where('user_id', \auth()->id())
            ->where('game_id', $currentHundredGame->id)
            ->where('game_type', 'hundred')
            ->where('win', 1)
            ->first();

        //**** لو لم يكسب من قبل هذه اللعبة, يتم تسجيله كلاعب في هذة الجولة
        if(!$winGameBefore){
            $playGameBefore = GamePlayer::where('user_id', \auth()->id())
                ->where('game_id', $currentHundredGame->id)
                ->where('game_type', 'hundred')
                ->where('numbers', null)
                ->where('active', 1)
                ->where('play', 0)
                ->where('win', 0)
                ->first();

            //**** هل قام اللاعب بالبدء باللعبة اولا قبل عملية اختيار الارقام
            if($playGameBefore)
            {
                $this->validate($request, [
                    'numbers' => 'required',
                ]);

                //**** يتم ادخال الارقام و جلب العمليات السابقة لهذا اللاعب لإلغاء اختيار الارقام الخاطئة مرة أخري
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

                //****** هل هناك آراء جمهور لهدة اللعبة
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

                    return $this->successMessage($data, 'الارقام المتوقعة');
                }else{
                    return $this->returnSuccessMessage('لا يوجد آراء جمهور لهدة اللعبة');
                }
            }else{
                return $this->returnSuccessMessage('يرجي البدء باللعبة أولا قبل اختيار الارقام');
            }

        }elseif($winGameBefore){
            //**** لو كسب من قبل هذه اللعبة, يتم تسجيله كرأي جَمهور
            return $this->returnSuccessMessage('لا يمكن عرض الجمهور لأنك بالفعل واحد من الجمهور');
        }
    }


}
