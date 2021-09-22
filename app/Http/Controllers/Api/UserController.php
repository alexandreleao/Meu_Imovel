<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function index()
    {
        $users = $this->user->paginate('10');

        return response()->json($users, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->all();

        if(!$request->has('password')|| !$request->get('password')){
            
            $message = new ApiMessages('É necessário informar uma senha para o usuário...');
            return response()->json($message->getMessage(), 401);
        }

        try {
            $user['password'] = bcrypt($user['password']);

            $user = $this->user->create($user); //Mass Asignment

            return response()->json([
                'data' =>[
                    'msg' => 'Usuário Cadastrado com sucesso!'
                ]
            ], 200);

        } catch (\Exception $e) {

            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }


        return response()->json($request->all(), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $user = $this->user->findOrFail($id); 
            
            return response()->json([
                'data' => $user
            ], 200);

            } catch (\Exception $e) {
                $message = new ApiMessages($e->getMessage());
                return response()->json($message->getMessage(), 401);
            }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        if($request->has('password') && $request->get('password')){
           $data['password'] = bcrypt($data['password']); 
           
        }else{
            unset($data['password']);
        }

             try {

            $user = $this->user->findOrFail($id); 
            $user->update($data);
            return response()->json([
                'data' =>[
                    'msg' => 'Usuário atualizado com sucesso!'
                ]
            ], 200);

            } catch (\Exception $e) {

                $message = new ApiMessages($e->getMessage());
                return response()->json($message->getMessage(), 401);
            }


             return response()->json($request->all(), 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        try {

            $user = $this->user->findOrFail($id); 
            $user->delete();
            return response()->json([
                'data' =>[
                    'msg' => 'Usuário removido com sucesso!'
                ]
            ], 200);

            } catch (\Exception $e) {

                $message = new ApiMessages($e->getMessage());
                return response()->json($message->getMessage(), 401);
            }
    }
}
