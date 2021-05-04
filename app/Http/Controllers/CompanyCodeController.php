<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\CompanyCode;

class CompanyCodeController extends Controller
{
    /**
     * Resive el codigo y busca si existe en caso de que si devuelve todos los detalles 
     *
     * @return \Illuminate\Http\Response
     */
    public function searchCode(Request $request)
    {
        $code = $request->only('code');
        $companyCode = CompanyCode::where('code',$code)->get();
        $valor = count($companyCode);
        if($valor==1){
            $companyCode['status']=true;
            return response()->json($companyCode, 200); 
        }else{
            $companyCode['status']=false;
            return response()->json($companyCode, 200);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $coder = $request->only('code');
        $code = $coder['code'];
        $companycode = CompanyCode::find($code);
        $requestQuotitation_id = $companycode['request_quotitations_id'];
        $deils = RequestDetail::where('request_quotitations_id',$requestQuotitation_id)->get();
        $companycode['details'] = $deils;
        return response()->json($requestQuotitation,200);
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
        //
    }
}