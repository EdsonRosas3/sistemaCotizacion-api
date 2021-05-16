<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\EmailModel;
use App\CompanyCode;

class EmailController extends Controller
{
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
     * resive los emails y la descripcion del mensage que se enviara a las empresas o a la empresa
 * y resive el id a la solicitud a la que pertenece
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {

        $emails = $request->emails;
        $descrption = $request->description;
        foreach ($emails as $key => $email) {
            $input['email']=$email;
            $input['request_quotitations_id']=$id;
            $input['code']=substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
            //dd($input);
            $request['code']=$input['code'];
            $request['link']="http://127.0.0.1:8000";
            //$description=$description.'\n'.'Codigo de acceso: '.$input['code'].'\n'.'Url del sistema: http:/loaclhost';
            //$request['description']=$description;
            //dd($request);
            Mail::to($email)->send(new EmailModel($request));
            dd($input);
            CompanyCode::create($input);
            
        }
        return response()->json(['result'=>"El mensaje ha sido enviado"],200); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
