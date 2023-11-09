<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\binance;
use Illuminate\Http\Request;
use App\Models\recommendation;
use App\Models\transactionUser;
use PhpParser\Node\Stmt\Return_;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\ChekRequestUser;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\updatedUserRequest;
use App\Http\Resources\UserResourceAdmin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;


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


        $user = auth('api')->user();
        $comming_afflite = $user->comming_afflite;
        $authcontroller = new AuthController();
        $code = $authcontroller->generate_affiliate_code();
        $dymnamikeLink = $authcontroller->dymnamikeLink($code);
        $request['affiliate_link'] = $dymnamikeLink;
        $request['affiliate_code'] = $code;
        $request['comming_afflite'] = $comming_afflite;


        $user = User::create($request->all());
        $pass = Hash::make($request['password']);
        $user->password = $pass;
        $user->save();

        if ($request->has('plan') && $request['state'] == 'admin') {
            $user->Role()->attach($request['plan']);
        }
        return response()->json([
            'state' => 'success add',
            'user' => $user,
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


        $request = User::with(['Role', 'binanceloges'])->find($id);

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

        if ($request->has('plan') &&  $user->state == 'admin') {
            $user->Role()->sync($request['plan']);
        }
        if ($request->has('password')) {
            $password = $request->input('password');

            if (!empty($password)) {
                $request['password'] = Hash::make($password);
            } else {
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
            'success' => true,
            'massage' => "Request is Delete"
        ]);
    }



    public function get_user(Request $request)
    {
        $page = $request->input('page', 1); // Get the requested page from the request parameters

        if ($request->input('state') == 'user') {
            $users = User::where('state', 'user')
                ->with(['bot_transfer', 'Role'])
                ->paginate(15, ['*'], 'page', $page);

            return response()->json([
                'data' => UserResource::collection($users),
                'meta' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'next_page' => $users->nextPageUrl(),
                    'total' => $users->total(),
                ],
            ]);
        }
        if ($request->input('state') == 'admin') {
            $users = User::where('state', 'admin')
                ->with(['bot_transfer', 'Role'])
                ->paginate(15, ['*'], 'page', $page);

            return response()->json([
                'data' => UserResource::collection($users),
                'meta' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'next_page' => $users->nextPageUrl(),
                    'total' => $users->total(),
                ],
            ]);
        }
        if ($request->input('state') == 'super_admin') {
            $users = User::where('state', 'super_admin')
                ->with(['bot_transfer', 'Role'])
                ->paginate(15, ['*'], 'page', $page);

            return response()->json([
                'data' => UserResource::collection($users),
                'meta' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'next_page' => $users->nextPageUrl(),
                    'total' => $users->total(),
                ],
            ]);
        }
        if ($request->input('state') == 'support') {
            $users = User::where('state', 'support')
                ->with(['bot_transfer', 'Role'])
                ->paginate(15, ['*'], 'page', $page);

            return response()->json([
                'data' => UserResource::collection($users),
                'meta' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'next_page' => $users->nextPageUrl(),
                    'total' => $users->total(),
                ],
            ]);
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
        $results = User::select('id', 'name', 'affiliate_code')->where('comming_afflite', $comming_afflite)->get();
        // return $results;
        if (!$results) {
            return response()->json(['message' => 'Request not found'], 404);
        }
        return response()->json($results);
    }
    // Waiting 
    public function get_user_parent($comming_afflite)
    {
        $results = User::select('id', 'name', 'affiliate_code')->where('affiliate_code', $comming_afflite)->get();
        if (!$results) {
            return response()->json(['message' => 'Request not found'], 404);
        }
        return response()->json($results);
    }

    public function selectUserFromPlan($id)
    {


        $user = User::where('plan_id', $id)->get();

        return $user;
    }

    // Get Sum Money From All Users 
    public function getSumMoney(Request $request)
    {
        $user = auth('api')->user();
        // Check if user is admin
        if ($user->state == 'super_admin') {
            $users = User::all();
            $sum = 0;
            foreach ($users as $user) {
                $sum += $user->money;
            }
            // MAke sum Double floot 
            $sum = number_format($sum, 2, '.', '');
            return response()->json([
                'sum' => $sum,
            ]);
        }
    }

    public function getAllUserAdminData(Request $request)
    {
        $user = auth('api')->user();
        if ($user->state == 'super_admin') {
            $usersId = Admin::pluck('user_id')->toArray();
            $users = User::where('state', 'admin')->whereIn('id', $usersId)->get();
            return response()->json([
                'users' => UserResourceAdmin::collection($users),
            ]);
        }
        return response()->json([
            'message' => 'You are not allowed to access this resource',
        ]);
    }

    public function getAllUserAdmin(Request $request)
    {
        $user = auth('api')->user();
        if ($user->state == 'super_admin') {
            $usersId = Admin::pluck('user_id')->toArray();
            $users = User::select('id', 'name')->where('state', 'admin')->whereIn('id', $usersId)->get();
            return response()->json([
                'users' => $users,
            ]);
        }
        return response()->json([
            'message' => 'You are not allowed to access this resource',
        ]);
    }

    public function getAllUserAdminRecommendation($id)
    {
        $user = auth('api')->user();
        if ($user->state == 'super_admin') {
            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    'message' => 'This user not found',
                ]);
            }
            $allRecommendation = $user->recommendation;
            return response()->json([
                'userName' => $user->name,
                'countRecommendation' => count($allRecommendation),
                'allRecommendation' => $allRecommendation,
            ]);
        }
        return response()->json([
            'message' => 'You are not allowed to access this resource',
        ]);
    }


    // get_money_user_transaction
    public function get_money_user_transaction(Request $request)
    {
        $user = auth('api')->user();
        if ($user->state == 'super_admin') {
            $money = transactionUser::pluck('amount')->toArray();
            $sum = array_sum($money);
            $lastMonth = date('Y-m-d', strtotime('-1 month'));
            $moneyLastMonth = transactionUser::where('created_at', '>=', $lastMonth)->pluck('amount')->toArray();
            $sumLastMonth = array_sum($moneyLastMonth);
            return response()->json([
                'sum' => $sum,
                'sumLastMonth' => $sumLastMonth,
            ]);
        }
        return response()->json([
            'message' => 'You are not allowed to access this resource',
        ]);
    }
}
