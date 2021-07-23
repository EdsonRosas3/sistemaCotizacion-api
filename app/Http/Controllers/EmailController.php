<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\EmailModel;
use App\CompanyCode;
use App\PrintedQuote;

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
        foreach ($emails as $key => $email) {
            $input['email']=$email;
            $input['request_quotitations_id']=$id;
            $input['code']=substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
            $request['code']=$input['code'];
            $request['link']="https://sistemacontizacionumss.web.app/ingresoCodigo";
            //crear codigo de solicitud de cotizacion
            $codQuotation=0;
            $lastPrintedQuote = PrintedQuote::pluck('idQuotation')->last();
            $lastEmailQuote = CompanyCode::pluck('idQuotation')->last();
            if($lastPrintedQuote!=null && $lastEmailQuote!=null){
                if($lastPrintedQuote>$lastEmailQuote){
                    $codQuotation = $lastPrintedQuote+1;
                }
                else{
                    $codQuotation = $lastEmailQuote+1;
                }
            }
            else{
                if($lastPrintedQuote==null && $lastEmailQuote==null){
                    $codQuotation=1;
                }
                else{
                    if($lastEmailQuote==null){
                        $codQuotation = $lastPrintedQuote+1;
                    }
                    else{
                        $codQuotation = $lastEmailQuote+1;
                    }
                }
            }
            $input['idQuotation']=$codQuotation;
            CompanyCode::create($input);
            Mail::to($email)->send(new EmailModel($request));
            }
            return response()->json(['result'=>"El mensaje ha sido enviado exitosamente!"],200); 
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
