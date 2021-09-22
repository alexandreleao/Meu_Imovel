<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\RealState;
use App\Http\Controllers\Controller;
use App\Http\Requests\RealStateRequest;

class RealStateController extends Controller
{
    private $realState;

    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
    }

    public function index()
    {
        $realState = $this->realState->paginate('10');

        return response()->json($realState, 200);
    }

    public function show($id)
    {
        try {

            $data = $this->realState->findOrFail($id); 
            
            return response()->json([
                'data' => $data
            ], 200);

            } catch (\Exception $e) {
                $message = new ApiMessages($e->getMessage());
                return response()->json($message->getMessage(), 401);
            }
    }

    public function store(RealStateRequest $request)
    {
        $data = $request->all();

        try {

            $realState = $this->realState->create($data); //Mass Asignment

            return response()->json([
                'data' =>[
                    'msg' => 'Imóvel Cadastrado com sucesso!'
                ]
            ], 200);

        } catch (\Exception $e) {

            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }


        return response()->json($request->all(), 200);
    }

         

        public function update(RealStateRequest $request, $id)
        {
            $data = $request->all();

             try {

            $realState = $this->realState->findOrFail($id); 
            $realState->update($data);
            return response()->json([
                'data' =>[
                    'msg' => 'Imóvel atualizado com sucesso!'
                ]
            ], 200);

            } catch (\Exception $e) {

                $message = new ApiMessages($e->getMessage());
                return response()->json($message->getMessage(), 401);
            }


             return response()->json($request->all(), 200);

        }

          public function destroy($id)
         {
           

             try {

            $realState = $this->realState->findOrFail($id); 
            $realState->delete();
            return response()->json([
                'data' =>[
                    'msg' => 'Imóvel removido com sucesso!'
                ]
            ], 200);

            } catch (\Exception $e) {

                $message = new ApiMessages($e->getMessage());
                return response()->json($message->getMessage(), 401);
            }

        }
}
