<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait GeneralTrait
{

    public function getCurrentLang()
    {
        return app()->getLocale();
    }

    // public function editValidate(Request $request, array $rules, array $status=["422"], array $messages = [], array $customAttributes = [])
    // {
    //     return $this->getValidationFactory()->make(
    //         $request->all(),
    //         $rules,
    //         $status,
    //         $messages,
    //         $customAttributes
    //     )->validate();
    // }

    public function successMessage($data, $message = "")
    {
        return [
            'status' => "200",
            'message' => $message,
            'data' => $data
        ];
    }
    public function specialSuccessMessage($message = "", $max_limit="", $available="" ,$data)
    {
        return [
            'status' => "200",
            'message' => $message,
            'max_limit' => $max_limit.' '.env('APP_CURRENCY'),
            'available' => $available.' '.env('APP_CURRENCY'),
            'data' => $data
        ];
    }

    public function successTotalMessage($data, $message = "", $total=777)
    {
        return [
            'status' => "200",
            'message' => $message,
            'total' => $total.' '.env('APP_CURRENCY') ,
            'data' => $data
        ];
    }
    public function returnSuccessMessage($message = "")
    {
        return [
            'status' => "200",
            'message' => $message,
            "data" => '',
        ];
    }
    public function returnErrorMessage($message, $status= "400")
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            "data" => '',
        ]);
    }

    public function responseValidationJsonFailed($message = "Fail")
    {
        return response()->json([
            'status' => "400",
            'message' => $message,
            "data" => '',
        ], 200);
    }





    ////////////////////////////////////////////////////////////
    public function returnError($errNum, $msg)
    {
        return response()->json([
            'status' => false,
            'errNum' => $errNum,
            'msg' => $msg
        ]);
    }

    public function responseJsonFailed($status = 422 , $message = "Fail")
    {
        return response()->json([
            "success" => false,
            "status" => $status,
            "message" => $message,
        ], $status);
    }
    // public function returnSuccessMessage($msg = "", $errNum = "2000")
    // {
    //     return [
    //         'status' => true,
    //         'errNum' => $errNum,
    //         'msg' => $msg
    //     ];
    // }

    public function returnData($key, $value, $msg = "")
    {
        return response()->json([
            'status' => true,
            'errNum' => "2000",
            'msg' => $msg,
            $key => $value
        ]);
    }


    /////////////////////////////////////////////////////////////////
        public function returnValidationErrorNew($validator)
    {
        return $this->returnError('011', $validator->errors());
    }

    public function returnValidationError($code = "E001", $validator)
    {
        return $this->returnError($code, $validator->errors()->first());
    }
    //-------------------
    public function returnCodeAccordingToInput($validator)
    {
        $inputs = array_keys($validator->errors()->toArray());
        $code = $this->getErrorCode($inputs[0]);
        return $code;
    }
    //-------------------
    public function getErrorCode($input)
    {
        if ($input == "name")
            return 'E0011';

        else if ($input == "password")
            return 'E002';

        else if ($input == "mobile")
            return 'E003';

        else if ($input == "id_number")
            return 'E004';

        else if ($input == "birth_date")
            return 'E005';

        else if ($input == "agreement")
            return 'E006';

        else if ($input == "email")
            return 'E007';

        else if ($input == "city_id")
            return 'E008';

        else if ($input == "insurance_company_id")
            return 'E009';

        else if ($input == "activation_code")
            return 'E010';

        else if ($input == "longitude")
            return 'E011';

        else if ($input == "latitude")
            return 'E012';

        else if ($input == "id")
            return 'E013';

        else if ($input == "promocode")
            return 'E014';

        else if ($input == "doctor_id")
            return 'E015';

        else if ($input == "payment_method" || $input == "payment_method_id")
            return 'E016';

        else if ($input == "day_date")
            return 'E017';

        else if ($input == "specification_id")
            return 'E018';

        else if ($input == "importance")
            return 'E019';

        else if ($input == "type")
            return 'E020';

        else if ($input == "message")
            return 'E021';

        else if ($input == "reservation_no")
            return 'E022';

        else if ($input == "reason")
            return 'E023';

        else if ($input == "branch_no")
            return 'E024';

        else if ($input == "name_en")
            return 'E025';

        else if ($input == "name_ar")
            return 'E026';

        else if ($input == "gender")
            return 'E027';

        else if ($input == "nickname_en")
            return 'E028';

        else if ($input == "nickname_ar")
            return 'E029';

        else if ($input == "rate")
            return 'E030';

        else if ($input == "price")
            return 'E031';

        else if ($input == "information_en")
            return 'E032';

        else if ($input == "information_ar")
            return 'E033';

        else if ($input == "street")
            return 'E034';

        else if ($input == "branch_id")
            return 'E035';

        else if ($input == "insurance_companies")
            return 'E036';

        else if ($input == "photo")
            return 'E037';

        else if ($input == "logo")
            return 'E038';

        else if ($input == "working_days")
            return 'E039';

        else if ($input == "insurance_companies")
            return 'E040';

        else if ($input == "reservation_period")
            return 'E041';

        else if ($input == "nationality_id")
            return 'E042';

        else if ($input == "commercial_no")
            return 'E043';

        else if ($input == "nickname_id")
            return 'E044';

        else if ($input == "reservation_id")
            return 'E045';

        else if ($input == "attachments")
            return 'E046';

        else if ($input == "summary")
            return 'E047';

        else if ($input == "user_id")
            return 'E048';

        else if ($input == "mobile_id")
            return 'E049';

        else if ($input == "paid")
            return 'E050';

        else if ($input == "use_insurance")
            return 'E051';

        else if ($input == "doctor_rate")
            return 'E052';

        else if ($input == "provider_rate")
            return 'E053';

        else if ($input == "message_id")
            return 'E054';

        else if ($input == "hide")
            return 'E055';

        else if ($input == "checkoutId")
            return 'E056';

        else
            return "";
    }


}
