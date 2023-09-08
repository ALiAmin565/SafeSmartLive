<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PayMopController extends Controller
{




    // public function all(){
    //    return  $this->paymobAuth();
    //    return $this->orderregistration();
    //     return $this->payment();

    // }
    //first step
     public function PaymobAuth(){
        $request=Http::post('https://accept.paymob.com/api/auth/tokens',[
            "api_key"=>"ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SmpiR0Z6Y3lJNklrMWxjbU5vWVc1MElpd2ljSEp2Wm1sc1pWOXdheUk2T0RFMU9USXlMQ0p1WVcxbElqb2lhVzVwZEdsaGJDSjkuRmZBd01qaHlVR05RcXZQYWV6cTE1QnFVV2ZfZWRoQUdSSW5sU2FWekpRbHdIb1g2NURpaWFhTThfeFBiWHZuUUczTV96VDlHbm1mWVA3N0VwTUJjeEE="
        ]);
        $token= $request['token'];
        return $token;
     }
    //second step
    public function orderregistration()

       {

            $order=Http::post('https://accept.paymob.com/api/ecommerce/orders',[
                'auth_token'=>$this->paymobAuth(),
                'delivery_needed'=>'false',
                'amount_cents'=>100,
                "items"=> [

                    ],

                ]);
                return $order['id'];

       }
       //thied step
       public function payment(){
        {

            $request=Http::post('https://accept.paymob.com/api/acceptance/payment_keys',[
            "auth_token"=>$this->paymobAuth(),
            "amount_cents"=> "100",
            "expiration"=> 3600,
            "order_id"=>$this->orderregistration(),
            //$this->orderregistration(),
            "billing_data"=>[
              "apartment"=> "NA",
              "email"=> "ahmedsamir11711@gmail.com",
              "floor"=> "NA",
              "first_name"=> "samir",
              "street"=> "NA",
              "building"=> "NA",
              "phone_number"=> "+01016158010",
              "shipping_method"=> "PKG",
              "postal_code"=> "01898",
              "city"=> "NA",
              "country"=> "CR",
              "last_name"=> "Ahmed",
              "state"=> "Utah",
            ],
            "currency"=> "EGP",
            "integration_id"=> 3892711
        ]);
        $token=  $request['token'];

      return redirect("https://accept.paymob.com/api/acceptance/iframes/765533?payment_token=".$token);

    }
}
    //fourth step

}

