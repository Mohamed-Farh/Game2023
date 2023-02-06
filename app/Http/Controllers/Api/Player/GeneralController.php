<?php

namespace App\Http\Controllers\Api\Player;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PriceDetailsRequest;
use App\Http\Requests\Api\ShopDetailsRequest;
use App\Http\Resources\AppStartPageResource;
use App\Http\Resources\EmailResource;
use App\Http\Resources\InformationResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\PhoneResource;
use App\Http\Resources\PlayerPriceResource;
use App\Http\Resources\PriceResource;
use App\Http\Resources\ShopResource;
use App\Http\Resources\SocialMediaResource;
use App\Models\AppStartPage;
use App\Models\ContactMessage;
use App\Models\Email;
use App\Models\Information;
use App\Models\Notification;
use App\Models\Phone;
use App\Models\PlayerPrice;
use App\Models\Price;
use App\Models\Shop;
use App\Models\SocialMedia;
use App\Models\UserAddress;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;


class GeneralController extends Controller
{
    use GeneralTrait;

    public function appStartPages(Request $request)
    {
        $pages = AppStartPage::whereStatus(1)->get();
        return $this->successMessage(AppStartPageResource::collection($pages), 'App Start Pages');
    }

    public function getPhones(Request $request)
    {
        $phones = Phone::whereStatus(1)->get();
        return $this->successMessage(PhoneResource::collection($phones), 'App Phones');
    }

    public function getSocialMedia(Request $request)
    {
        $socialMedias = SocialMedia::whereStatus(1)->get();
        return $this->successMessage(SocialMediaResource::collection($socialMedias), 'App Social Media');
    }

    public function getEmails(Request $request)
    {
        $emails = Email::whereStatus(1)->get();
        return $this->successMessage(EmailResource::collection($emails), 'App Email');
    }

    public function getAboutUs(Request $request)
    {
        $record = Information::whereType('About Us')->whereStatus(1)->first();
        return $this->successMessage(new InformationResource($record), 'About Us');
    }

    public function getPrivacy(Request $request)
    {
        $record = Information::whereType('Privacy')->whereStatus(1)->first();
        return $this->successMessage(new InformationResource($record), 'Privacy');
    }

    public function getRule(Request $request)
    {
        $record = Information::whereType('Rules')->whereStatus(1)->first();
        return $this->successMessage(new InformationResource($record), 'Rules && Conditions');
    }

    public function sendContactMessage(Request $request)
    {
        $this->validate($request, [
            'message' => 'required|min:4|string',
        ]);

        try {
            $user = \auth()->user();
            $address = UserAddress::whereUserId(\auth()->id())->first();
            $input['name']          = $user->full_name;
            $input['company']       = $request->company;
            $input['email']        = $user->email;
            $input['mobile']        = $user->mobile;
            if (!empty($address)){
                $input['country_id']    = $address->country_id;
                $input['state_id']      = $address->state_id;
                $input['city_id']       = $address->city_id;
            }
            $input['message']          = $request->message;
            $input['status']          = '1';
            ContactMessage::create($input);
            return $this->returnSuccessMessage('Your message has been sent successfully');

        }catch (\Exception $e) {
            return $this->returnErrorMessage('Sorry! Please Try Again !', '422');

        }
    }

    //***********************************    Home   ***************************************************
    public function latestWinGame()
    {
        $latest_price = PlayerPrice::where('user_id', \auth()->id())
            ->whereActive(1)
            ->latest('id')
            ->first();
        if($latest_price){
            return $this->successMessage(new PlayerPriceResource($latest_price), 'The last Prise You Won');
        }else{
            return $this->returnSuccessMessage('You Didn\'t win any Prices Before');
        }

    }

    public function priceDetails(PriceDetailsRequest $request)
    {
        $price = Price::whereId($request->id)->first();
        return $this->successMessage(new PriceResource($price), 'Price Details');
    }

    public function myNotifications(Request $request)
    {
        $player = \auth()->user();
        $notifications = $player->notification;
        return $this->successMessage(NotificationResource::collection($notifications), 'Notifications');
    }
    public function readNotification(Request $request)
    {
        $notification = Notification::whereId($request->id)->first();
        $notification->update([
            'read_at' => now(),
        ]);
        return $this->returnSuccessMessage('Notification Status Changed');
    }


    public function shops(Request $request)
    {
        $shops = Shop::whereActive(1)->latest('free')->get();
        return $this->successMessage(ShopResource::collection($shops), 'All Offers');
    }
    public function shopDetails(ShopDetailsRequest $request)
    {
        $shop = Shop::whereId($request->id)->first();
        return $this->successMessage(new ShopResource($shop), 'Offer Details');
    }
}
