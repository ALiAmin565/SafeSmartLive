<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\ChekRequestUser;
 use Illuminate\Database\Eloquent\SoftDeletes;
 use Illuminate\Database\Eloquent\Model;
 use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\updatedUserRequest;
use Illuminate\Support\Facades\Hash;


class All_UserController extends Controller
{
    use SoftDeletes;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return 'not found';

     }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {


        $user=auth('api')->user();
        $comming_afflite=$user->comming_afflite;
        $authcontroller=new AuthController();
        $code=$authcontroller->generate_affiliate_code();
        $dymnamikeLink=$authcontroller->dymnamikeLink($code);
        $request['affiliate_link']=$dymnamikeLink;
        $request['affiliate_code']=$code;
        $request['comming_afflite']=$comming_afflite;


        $user=User::create($request->all());
        $pass=Hash::make($request['password']);
        $user->password=$pass;
        $user->save();

        if($request->has('plan') && $request['state']=='admin')
        {
            $user->Role()->attach($request['plan']);
        }
         return response()->json([
            'state'=>'success add',
            'user'=>$user,
         ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


         $request = User::with(['Role','binanceloges'])->find($id);

        if (!$request) {
            return response()->json(['message' => 'Request not found'], 404);
        }
        
        
        // return $request;
        return UserResource::make($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updatedUserRequest $request, $id)
    { 


        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Request not found'], 404);
        }

        if($request->has('plan') &&  $user->state =='admin')
        {
            $user->Role()->sync($request['plan']);
        }
        if ($request->has('password')) {
                         $password = $request->input('password');

                   if (!empty($password)) {
         $request['password'] = Hash::make($password);
               

    }else{
     $request['password'] = $user->password;
    }
  
} 

$store = $user->update($request->all());

return [
    'state' => 'success update',
    'user' => UserResource::make($user),
];

    }
    
    
    // 
      

    public function destroy($id)
    {

        // not forget soft delete
        $request = User::find($id);

        if (!$request) {
            return response()->json(['message' => 'Request not found'], 404);
        }

        $request->Role()->detach();


        $request->delete();

          return response()->json([
            'success'=>true,
            'massage'=>"Request is Delete"
        ]);
    }



    public function get_user($request)
    {

        // return 150;
    if($request == 'user')
    {
        // return 150;
        return UserResource::collection(User::where('state',$request)->with(['bot_transfer'])->get());

    }if($request == 'admin')
    {
        return UserResource::collection(User::where('state',$request)->with(['bot_transfer'])->get());

    }
    if($request == 'super_admin')
    {

        return UserResource::collection(User::where('state',$request)->with(['bot_transfer'])->get());

    }



     }

     public function serach($query)
     {
      
            $results = User::where('name', 'like', '%' . $query . '%')
        ->orWhere('email', 'like', '%' . $query . '%')
        ->orWhere('phone', 'like', '%' . $query . '%')
        ->get();
  
        return response()->json($results);
     }
        // for get allUser
     public function get_all_subscrib($comming_afflite)
     {
            // return 150;
        $results = User::select('id','name')->where('comming_afflite',$comming_afflite)->get();
        return $results
;
        if (!$results) {
            return response()->json(['message' => 'Request not found'], 404);
        }
        return response()->json($results);
     }

 public function selectUserFromPlan($id)
 {
      
 
$user=User::where('plan_id',$id)->get();

return $user;

 }


}
