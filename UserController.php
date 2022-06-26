<?php


namespace App\Http\Controllers\api;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller as Controller;

use App\User;

//use Illuminate\Support\Facades\Auth;
use Auth;
use Validator;


class UserController extends Controller

{


    public $successStatus = 200;


    /**

     * login api

     *

     * @return \Illuminate\Http\Response

     */

    public function login(Request $request){

 
    $user=User::where(['name'=>$request->name,'mobile' => $request->mobile])->first();
        if($user){

           $success['token'] =  $user->createToken('useraccess')->accessToken;

            return response()->json(['success' => $success], $this->successStatus);

        }

        else{

            return response()->json(['error'=>'Unauthorised'], 401);

        }

    }


    /**

     * Register api

     *

     * @return \Illuminate\Http\Response

     */

    public function register(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'name' => 'required',
            'mobile'=>'iran_mobile',
             'state_id' => 'required',
              'city_id' => 'required',
               'area_id' => 'required'

               // 'email' => 'required|email',

            //'password' => 'required',

            //'c_password' => 'required|same:password',

        ]);


        if ($validator->fails()) {
           //return dd($request->all());

            return response()->json(['error'=>$validator->errors()], 401);            

        }


        $input = $request->all();

    
     $user = User::updateOrCreate(['mobile'=>$input['mobile']],
            collect($input)->except(['mobile'])->toArray());

  
  $user=User::find(30);

  return $user->tokens()->first()->revoke();
      
          
        $success['token'] =  $user->createToken('uaccess')->accessToken;
        $success['name'] =  $user->name;
        $success['id'] =  $user->id;


        return response()->json(['success'=>$success], $this->successStatus);

    }

    public function edit(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'name' => 'required',
            'state_id' => 'required',
              'city_id' => 'required',
               'area_id' => 'required'

        ]);


        if ($validator->fails()) {
           //return dd($request->all());

            return response()->json(['error'=>$validator->errors()], 401);            

        }


        $input = $request->all();

      $tuser = Auth::user();
      $tuser->update($request->all());



        return response()->json(['success'=>collect($tuser)->except(['created_at','updated_at'])], $this->successStatus);

    }


    /**

     * details api

     *

     * @return \Illuminate\Http\Response

     */

    public function details()

    {

         $user = Auth::guard('api')->user();

         return $user->token();

        return response()->json(['success' => $user], $this->successStatus);

    }

       public function logout(Request $request)

    {

        $user = Auth::user();
         
         $user->tokens;
         $g=$user->token()->revoke();

         return $g;


    }

}