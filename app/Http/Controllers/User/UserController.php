<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();
        return response()->json(['data' => $user], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
      
        // $rules = [
        //     'name' => 'required|unique:users',
        //     'password'=> 'required|min:8|confirmed',
        //     'email' => 'required|email|unique:users'
        // ];
        // $this->validate($request, $rules);

        $validator = \Validator::make($request->all(), [
            'name' => 'required|unique:users',
            'password'=> 'required|min:8|confirmed',
            'email' => 'required|email|unique:users'
          ]);

          if($validator->fails())
          {
            return response()->json(['error' =>  'formulir yang anda masukan salah'], 400);
          }
          else 
          {
            $data = $request->all();
            $data['password'] = bcrypt($request->password);
            $data['verified'] = User::UNVERIFIED_USER;
            $data['verification_token'] = User::generateVerificationCode();
            $data['admin'] = User::REGULAR_USER;
        
            $user = User::create($data);
            return response()->json(['data' =>  $user], 201);
          }
       
        // 
      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json(['data' => $user], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        

        $validator = \Validator::make($request->all(), [
            'password'=> 'min:8|confirmed',
            'email' => 'email|unique:users, email'. $user->id,
            'admin' => 'in:'.User::ADMIN_USER . ', '. User::REGULAR_USER,
        ]);

        if($validator->fails())
        {
            return response()->json(['error' =>  'formulir yang anda masukan salah'], 400);
        }

        if($request->has('name'))
        {
            
            $user->name = $request->name;
        }

        if($request->has('email') && $user->email != $request->email)
        {
            $user->verified = User::VERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }

        if($request->has('password') )
        {
            $user->password = $request->password;
        }

        if($request->has('admin') )
        {
            if(!$user->isVerified())
            {
                return response()->json(['error' => 'hanya user yang terverifikasi yang bisa menjadi admin', 'code'=> 499], 499);
                $user->admin = $request->admin;
            }


        }

        if(!$user->isDirty()){
            return response()->json(['error' => 'Anda tidak mengubah data apapun', 'code'=> 422], 422);
        }else
        {
            $user->save();
        }
        return response()->json(['data' => $user], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response('deleted', 204);
    }
}
