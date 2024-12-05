<?php

namespace App\Classes;

use Carbon\Carbon;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class FormatResponse extends Controller{
    /**
	 * Formato de respuesta con Json
	 * @param $status
	 * @param $data
	 *
	 * @return Json
	 */
	public function toJson($status, $data = null)
	{
        /*
		|---------------------------------------------------------------------------------------
		| Definir salida de respuesta
		|---------------------------------------------------------------------------------------
		*/
		
		if($data !== null){
			$response = [
				'meta' => [
					'succes' => true,
					'message' => $status
				],
				'data' => $data
			];
        }else{
			$response = [
				'meta' => [
					'succes' => true,
					'errors' => [$status]
				]
			];
		}

        /*
        |---------------------------------------------------------------------------------------
		| Transformar la respuesta a json
		|---------------------------------------------------------------------------------------
		*/
        // $response = Response::json($response);

        return $response;
	}

	/*
	|---------------------------------------------------------------------------------------
	| Estado de respuesta OK
	|---------------------------------------------------------------------------------------
	*/
	public function estadoExitoso($data = null)
	{
		$mensaje= "Procesado con éxito";
		return $data;
    }

	public function estadoRegistroExitosoSeguridad($data = null)
	{
		$mensaje= "Registro existoso";
		return $data;
    }

	/*
	|---------------------------------------------------------------------------------------
	| Estado de respuesta no encontrado
	|---------------------------------------------------------------------------------------
	*/
	public function estadoNoEncontrado($msj = null)
	{
		$mensaje = $msj == null ? "Resultado no encontrado" : $msj;
		return $mensaje;
    }

	/*
	|---------------------------------------------------------------------------------------
	| Estado de respuesta operación fallida en alguna parte del proceso
	|---------------------------------------------------------------------------------------
	*/
	public function estadoOperacionFallida($msj = null)
	{
		$mensaje = $msj == null ? "Error: Operación fallida" : $msj;
		return $mensaje;
    }

	/*
	|---------------------------------------------------------------------------------------
	| Estado de respuesta parametros incorrectos al enviar al servidor
	|---------------------------------------------------------------------------------------
	*/
	public function estadoParametrosIncorrectos($msj = null)
	{
        $mensaje = $msj == null ? "Error: Operación fallida" : $msj;
		return $mensaje;
    }

	/*
	|---------------------------------------------------------------------------------------
	| Estado de respuesta acceso denegado para el acceso a ciertos modulos o servicios
	|---------------------------------------------------------------------------------------
	*/
    public function accesoDenegado($msj = null)
	{
		$mensaje = $msj == null ? "Acceso no autorizado." : $msj;
		return $mensaje;
	}

	/*
	|---------------------------------------------------------------------------------------
	| Estado de respuesta acceso no autorizado 403 para permisos de API
	|---------------------------------------------------------------------------------------
	*/
	public function noPermitido($msj = null)
	{
		$mensaje = $msj == null ? "Acceso no autorizado." : $msj;
		return response()->json(['message' => $mensaje, 'code' => '403'], 403);
	}

}
