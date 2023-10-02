<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\OtpMail;
use Tymon\JWTAuth\Token;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordOtp;
use App\Models\commingAfllite;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\affiliate_userRequest;
use App\Http\Resources\affiliate_userRsource;




class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'create', 'checkOtp', 'me', 'sendOtp', 'reSendOtp', 'resetPassword', 'dymnamikeLink']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function create(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|unique:users|email',
                'password' => 'required|min:8',
                'phone' => 'required',

            ]
        );




        if ($validator->fails()) {
            // return json of errors object
            $response = [
                'success' => false,
                "errors" => $validator->errors()
            ];
            return response()->json($response, 200);
        }



        // Get Default affiliate code  if $request['comming_afflite'] is null
        // 1544687

        if ($request['comming_afflite'] == null) {
            $money = 0;
            $getcomming = commingAfllite::where('status', 1)->first();
            $comming = $request['comming_afflite'] = $getcomming['comming_affliate'];
            $getcomming->subscrib += 1;
            $getcomming->save();
        } else {
            $rules = [
            ];

            $validator = Validator::make(
                $request->all(),
                [

                    'comming_afflite' =>'required|exists:users,affiliate_code',


                ]
            );

            if ($validator->fails()) {
                // return json of errors object
                $response = [
                    'success' => false,
                    "errors" => $validator->errors()
                ];
                return response()->json($response, 200);
            }


            $comming = $request['comming_afflite'] = $request['comming_afflite'];
            $money = 10;
        }


        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'comming_afflite' => $comming,
            'plan_id' => 1,
            'password' => Hash::make($request['password']),
            'number_points' => $money,
        ]);


        $this->verifyEmail($request);



        return $this->login($request);
    }
    public function sendOtp(Request $request)
    {
        // Retrieve the user's email address from the request
        $email = $request->input('email');

        // Check if the email address exists in the database
        $user = User::where('email', $email)->count();
        // check if email is exist

        if ($user == 0) {
            // Email not found
            $response = [
                'success' => false,
                'message' => 'Email is not exist'
            ];
            return response()->json($response);
        }
        $user = User::where('email', $email)->first();


        // Generate a random 6-digit OTP
        $otp = rand(100000, 999999);

        // Store the OTP in the database for the user
        $user->otp = $otp;
        $user->save();
        $otpDataa = [

            'otp' => $otp,
            'subject' => "reset Password"

        ];
        // Send the OTP to the user's email address
        Mail::to($email)->send(new ResetPasswordOtp($otpDataa)); // Replace with your own mail class

        // Return a success response
        $response = [
            'success' => true,
            'message' => 'OTP sent successfully'
        ];
        return response()->json($response);
    }
    // cheackopt
    public function checkOtp(Request $request)
    {

        try {
            $email = $request->input('email');
            $otp = $request->input('otp');

            $user = User::where('email', $email)->first();
            $userOtp = $user->otp;


            if ($otp == $userOtp) {

                if ($request['action'] != "reset") {
                    $user->otp = null;
                    $user->verified = true;


                    $code = $this->generate_affiliate_code();
                    $user->affiliate_code = $code;
                    $user->email_verified_at = time();
                    // return $code;

                    $user->affiliate_link = $this->dymnamikeLink($code);

                    $user->save();
                    $user2 = User::where('affiliate_code', $user->comming_afflite)->first();
                    $user2->number_of_user = $user2->number_of_user + 1;
                    $user2->save();
                }



                $response = [
                    'success' => true,
                    'message' => 'OTP is valid'
                ];
            } else {
                // OTP is not valid for the given user
                $response = [
                    'success' => false,
                    'message' => 'Invalid OTP'
                ];
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function resetPassword(Request $request)
    {
        $email = $request->input('email');
        $otp = $request->input('otp');

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email address not found'
            ], 200);
        }
        $userOtp = $user->otp;


        if ($otp = $userOtp) {
            if ($request['action'] == "reset") {
                $user->otp = null;
                $user->password = Hash::make($request['password']);
                $user->save();
            }
            $response = [
                'success' => true,
                'message' => 'Password Changed successfully'
            ];
        } else {
            // OTP is not valid for the given user
            $response = [
                'success' => false,
                'message' => 'Invalid OTP'
            ];
        }

        return response()->json($response);
    }
    public function verfiy(Request $request)
    {

        $user = auth('api')->user();

        $otp = $request->otp;
        //  return $otp;
        if ($otp == $user->otp) {
            $user->verified = true;
            $user->otp = null;
            $code = $this->generate_affiliate_code();
            $user->affiliate_code = $code;
            $user->email_verified_at = time();
            // return $code;

            $user->affiliate_link = $this->dymnamikeLink($code);

            $user->save();
            $user2 = User::where('affiliate_code', $user->comming_afflite)->first();
            $user2->number_of_user = $user2->number_of_user + 1;
            $user2->save();

            $response = [
                'success' => true,
                'message' => 'OTP is valid'
            ];
        } else {

            $response = [
                'success' => false,
                'message' => 'Invalid OTP'
            ];
        }
        return response()->json($response);
    }

    function generate_affiliate_code()
    {
        $code = '';
        $chars = array_merge(range('A', 'Z'), range(0, 9));

        // Generate a code with 8 characters
        for ($i = 0; $i < 8; $i++) {
            $code .= $chars[array_rand($chars)];
        }

        // Check if the code already exists in the database
        $existing_users = User::where('affiliate_code', $code)->count();
        if ($existing_users > 0) {
            // If it does, generate a new one recursively
            return $this->generate_affiliate_code();
        }

        return $code;
    }
    public function login(Request $data)
    {
        $validator = Validator::make(
            $data->all(),
            [
                'email' => 'required|email:rfc,dns',
                'password' => 'required|min:8',
            ]
        );

        if ($validator->fails()) {
            // return json of errors object
            $response = [
                'success' => false,
                "errors" => $validator->errors()
            ];
            return response()->json($response, 200);
        }
        $user0 = User::where('email', $data['email'])->first();
        // return $user0;
        if (!$user0) {
            return response()->json([
                'success' => false,
                'message' => 'Email address not Exist'
            ], 200);
        }


        $credentials = $data->only(['email', 'password'], 400);
        // return $credentials;
        $token = auth('api')->attempt($credentials);
        // return $token;


        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Wrong Password'
            ], 200);
        }
        $remToken = $user0->remember_token;

        if ($remToken != null) {
            // return $remToken;
            if ($user0->state == "user") {

                JWTAuth::manager()->invalidate(new Token($remToken));
            }
        }

        $user = auth('api')->user();
        // return $user;
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'invalid token'
            ], 200);
        }
        $user->remember_token = $token;
        $user->save();
        $user->token = $token;


        $user->load(['plan' => function ($query) {
            $query->orderBy('id', 'desc');
        }])->get();



        return response()->json([
            'success' => true,

            'user' => $user
        ]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {


        $header = $request->header('Authorization');
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'invalid token'
            ], 200);
        }
        if ($user->affiliate_code == null) {
            $code = $this->generate_affiliate_code();
            $user->affiliate_code = $code;
            $user->email_verified_at = time();
            // return $code;

            $user->affiliate_link = $this->dymnamikeLink($code);

            $user->save();
            $user2 = User::where('affiliate_code', $user->comming_afflite)->first();
            $user2->number_of_user = $user2->number_of_user + 1;
            $user2->save();
        }
        $user->load(['plan' => function ($query) {
            $query->orderBy('id', 'desc');
        }])->get();
        $user->token = $header;

        return response()->json([
            'success' => true,
            "user" => $user
        ]);
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
    public function verifyEmail(Request $data)
    {
        // generate otp from 6 digits
        $otp = rand(100000, 999999);
        // send it to database
        try {
            $user = User::where('email', $data['email'])->firstOrFail();
            $user->otp = $otp;
            $user->save();
            // send Mail Otp
            $otpDataa = [
                'otp' => $otp,
                'subject' => "Verify Email"
            ];
            Mail::to($data['email'])->send(new OtpMail($otpDataa));

            return response()->json(['success' => true, 'message' => "OTP sent to email"]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function reSendOtp(Request $data)
    {
        // generate otp from 6 digits

        // send it to database
        try {
            $validator = Validator::make(
                $data->all(),
                [
                    'email' => 'required|email:rfc,dns|exists:users,email',
                ]
            );

            if ($validator->fails()) {
                // return json of errors object
                $response = [
                    'success' => false,
                    "errors" => $validator->errors()
                ];
                return response()->json($response, 200);
            }
            $email = $data['email'];
            $user = User::where('email', $email)->firstOrFail();

            // Then, retrieve the OTP from the user's record
            if (!$user) {


                return response()->json(['success' => false, 'message' => "Email not found"]);
            }
            $otp = $user->otp;
            if (!$otp) {
                return $this->sendOtp($data);
            }


            // send Mail Otp
            $otpDataaa = [

                'otp' => $otp,
                'subject' => $data['subject'] ?? 'Verify Email'


            ];
            if ($data['subject'] == "Verify Email") {
                Mail::to($data['email'])->send(new OtpMail($otpDataaa));
            } else {
                Mail::to($data['email'])->send(new ResetPasswordOtp($otpDataaa));
            }





            return response()->json(['success' => true, 'message' => "OTP sent to email"]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        // return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */



    //  for plus number of user

    public function number_user($affiliate_code)
    {
        $user = User::where('affiliate_code', $affiliate_code)->first();



        $add = $user->number_of_user + 1;
        $user->update(
            [
                'number_of_user' => $add
            ]
        );
    }


    public function dymnamikeLink($code)
    {

        // $code=$data0['code'];

        $jsonData = [
            'dynamicLinkInfo' => [
                'domainUriPrefix' => 'https://upvela.page.link',
                'link' => 'https://upvela.com/register?code=' . $code,
                'androidInfo' => [
                    'androidPackageName' => 'com.upvela.upvela',
                ],
                'iosInfo' => [
                    'iosBundleId' => 'com.upvela.upvela',
                ],

            ],
        ];




        $response0 = Http::post('https://firebasedynamiclinks.googleapis.com/v1/shortLinks?key=AIzaSyAa9-l9PJ2zONYEsqsN84c7JD_9Aue8_pc', $jsonData);

        // return $response0;

        $data = json_decode($response0);
        return $data->shortLink;
    }

    public function deleteUser(Request $request)
    {
        $user = auth('api')->user();
        $user->delete();
        return response()->json(['success' => true, 'message' => "User Deleted"]);
    }

    public function affiliate_user(affiliate_userRequest $request)
    {
        $user = User::where('id', $request['user_id'])->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'invalid token'
            ], 200);
        }
        $affiliate_code = $user->affiliate_code;


        return affiliate_userRsource::collection(User::with('plan')->where('comming_afflite', $affiliate_code)->get());
    }

    public function softDeleteUser()
    {
        $deletedUsers = User::onlyTrashed()
            ->where('deleted_at', '!=', null)
            ->get();
        return response()->json(['success' => true, 'message' => "User Deleted", 'data' => $deletedUsers]);
    }

    public function restoreSoftDeleteUser($id)
    {

        $user = User::onlyTrashed()->find($id);
        if ($user) {
            $user->restore();
            $user->deleted_at = null;
            $user->save();
        }

        return response()->json(['success' => true, 'message' => "User Restored"]);
    }

    // for fcm token
    public function fcmToken(Request $request)
    {

        $user = auth('api')->user();  // Get the authenticated user using the 'api' guard
        $user['fcm_token'] = $request['fcm'];
        $user->save();


        return response()->json([
            'success' => true,
            'user' => $user,
            'message' => 'FCM token updated successfully'
        ]);
    }
}
