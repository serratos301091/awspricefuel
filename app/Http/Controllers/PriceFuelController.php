<?php

namespace App\Http\Controllers;
use App\Models\CodePostal;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;


class PriceFuelController extends Controller
{
    


    public function priceFuel(Request $request){

        $data = json_decode($request->input('json',null),true);

        $d_estado =  $data['state'];
        $d_mnpio = $data['municipality'];

        $state= CodePostal::select('d_codigo','d_estado','d_mnpio')
        ->where('d_estado',$d_estado )
        ->where('d_mnpio',$d_mnpio)    
        ->get();


        
        if(count($state) > 0){

            foreach ($state as  $item => $val ) {

                $code[] = $val['d_codigo'];
                $codeState = $val['d_estado'];
                $codeMnpio = $val['d_mnpio'];   
        
            }


            $client = new Client(['base_uri' => 'https://api.datos.gob.mx']); 
            $getTotal = $client->request('GET', 'v1/precio.gasolina.publico');
            $valTotal = json_decode($getTotal->getBody()->getContents(),true);
            

            $response = $client->request('GET', 'v1/precio.gasolina.publico?pageSize='.$valTotal['pagination']['total']);
            $fuel = json_decode($response->getBody());

            foreach ($fuel->results as $data ){
                if(in_array($data->codigopostal, $code)){
                    $merge[] = array(
                        '_id' =>  $data->_id,
                        'calle' => $data->calle,
                        'rfc' => $data->rfc,
                        'razonsocial' => $data->razonsocial,
                        'date_insert' => $data->date_insert,
                        'numeropermiso' => $data->numeropermiso,
                        'fechaaplicacion' => $data->fechaaplicacion,
                        '﻿permisoid' => $data->﻿permisoid,
                        'longitude' => $data->longitude,
                        'latitude' => $data->latitude,
                        'codigopostal' =>  $data->codigopostal,
                        'colonia' => $data->colonia,
                        'municipio' => $codeMnpio,
                        'estado' => $codeState,
                        'regular' => $data->regular,
                        'premium' => $data->premium,
                        
    
                    );               
   
                }
            }
            
        }else{

            return "No  hay registros con esta busqueda";
        }

        if(count($merge)>0){

            return ['success'=> true,'results'=> $merge];
        }else{
            return ['success'=> false,'results'=> "No  hay registros con esta busqueda"];
        }
        

        //return $merge;

    }
}
