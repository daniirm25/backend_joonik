<?php

namespace App\Services;

use App\Models\User;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Classes\FormatResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LocationServices extends FormatResponse
{

    public function createLocation(Request $request)
    {
        $rules = [
            'name' => 'required',
            'code' => 'required',
            'description' => 'required'
        ];

        $messages = [
            'name.required' => 'El nombre de la sede es requerido.',
            'code.required' => 'El codigo es requerida.',
            'description.required' => 'La descripcion es requerida.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->errors()->getMessages()){
            return $this->toJson($this->estadoOperacionFallida($validator->getMessageBag()->all()));
        }else{
            try {
                DB::beginTransaction();
                $user_id = auth()->user()->id;
                $location = Location::where('name', $request->name)->orWhere('code', $request->code)->first();
                if($location){
                    return $this->toJson($this->estadoOperacionFallida('La sede ya existe'));
                }else{
                    $location = new Location();
                    $location->name = $request->name;
                    $location->code = $request->code;
                    $location->save();
                    DB::commit();
                    return $this->toJson($this->estadoExitoso('Sede creada exitosamente'), $location);
                }
            } catch (Throwable $th) {
                DB::rollback();
                Log::error($th->getMessage());
                return $this->toJson($this->estadoOperacionFallida($th->getMessage() . ' ' . $th->getLine()));
            }
        }
    }

    public function locations()
    {
        try {
            $locations = Location::get();
            if(!$locations->isEmpty()){
                return $this->toJson($this->estadoExitoso('Exitoso'),$locations);
            }
            return $this->toJson($this->estadoNoEncontrado('No se encuentran sedes registradas'));
        } catch (Throwable $th) {
            Log::error($th->getMessage());
            return $this->toJson($this->estadoOperacionFallida($th->getMessage() . ' ' . $th->getLine()));
        }
    }
}