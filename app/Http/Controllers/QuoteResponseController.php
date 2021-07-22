<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facade\File;
use Illuminate\Support\Facades\Storage;
use App\Quotation;
use App\Detail;
use App\RequestDetail;
use App\Business;
use App\CompanyCode;
use App\PrintedQuote;

class QuoteResponseController extends Controller
{
    public $successStatus = 200;
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
     * Guarda la cotizaciocion de respuesta que registra la EMPRESA
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storageQuote(Request $request){
        try {
            $idEmpresa = $request->only("business_id");
            if ($idEmpresa["business_id"]==0) {
                $dataEmpresa = $request->only("nameEmpresa","nit","rubro","email");
                $newEmpresa = Business::create($dataEmpresa);
                $idEmpresa["business_id"] = $newEmpresa["id"];
            }
            $quotationResponse = $request->only("offerValidity","deliveryTime","paymentMethod","answerDate","observation","company_codes_id");
            $quotationResponse["business_id"] = $idEmpresa["business_id"];
            $response['message']="Envio exitoso";
            $quotation = Quotation::create($quotationResponse);
            $response['id'] = $quotation->id;
            return response()->json(["response"=>$response], $this-> successStatus);
        } catch (\Throwable $th) {
            $response['message']="Algo salio mal por favor informa a la unidad cotizante.";
            return response()->json(["response"=>$response], $this-> successStatus);
        }
    }

    public function storageDetails(Request $request,$id){
        $detailResponse = $request->only("unitPrice","totalPrice","request_details_id","brand","industry","model","warrantyTime");
        $detailResponse['quotations_id'] = $id;
        $detail=Detail::create($detailResponse);
        return response()->json(["response"=>$detail->id], $this-> successStatus);
    }

    public function uploadFile(Request $request,$id)
    {
        $files = $request->file();
        foreach ($files as $file) {
            $filename = $file->getClientOriginalName();
        
            $filename= pathinfo($filename, PATHINFO_FILENAME);
            $name_File = str_replace(" ","_",$filename);
    
            $extension = $file->getClientOriginalExtension();
    
            $name = $id. "-" . $name_File . "." .$extension;
            $file->move(public_path('FilesResponseBusiness/'),$name);
        }
       
        return response()->json(["messaje"=>"Archivos guardados"]);
    }
    /**
     * Guarda la cotizaciocion de respuesta desde la  UNIDAD ADMINISTRATIVA
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function UAstorageQuote(Request $request){
        try {
            //idQuotation
            $idEmpresa = $request->only("idEmpresa");
            $id = $idEmpresa["idEmpresa"];
            $quotationResponse = $request->only("offerValidity","deliveryTime","paymentMethod","answerDate","observation");
            $codePrintedQuote=  $request->idQuotation;
            $printedQuote = PrintedQuote::where('idQuotation',$codePrintedQuote)->first();
            $emailBusiness= $printedQuote->email;
            if($emailBusiness==null){
                $printedQuote['email'] = $id;
                $emailBusiness = Business::where('id',$id)->pluck('email')->first();
                $printedQuote->update(['email' => $emailBusiness]);
            }
            $quotationResponse["printed_quotes_id"]=  $printedQuote->id;
            $quotationResponse["business_id"] = $id;
            $response['message']="Envio exitoso";
            $quotation = Quotation::create($quotationResponse);
            
            $response['id'] = $quotation->id;
            return response()->json(["response"=>$response], $this-> successStatus);
        } catch (\Throwable $th) {
            $response['message']="Algo salio mal por favor intente nuevamente.";
            return response()->json(["response"=>$response], $this-> successStatus);
        }
    }
    public function storageDetailsUA(Request $request,$id){
        $detailResponse = $request->only("unitPrice","totalPrice","request_details_id","brand","industry","model","warrantyTime");
        $detailResponse['quotations_id'] = $id;
        $detail = Detail::create($detailResponse);
        return response()->json(["response"=>$detail->id], $this-> successStatus);
    }
    public function uploadFileUA(Request $request,$id)
    {
        $files = $request->file();
        foreach ($files as $file) {
            $filename = $file->getClientOriginalName();
        
            $filename= pathinfo($filename, PATHINFO_FILENAME);
            $name_File = str_replace(" ","_",$filename);
    
            $extension = $file->getClientOriginalExtension();
    
            $name = $id. "-" . $name_File . "." .$extension;
            $file->move(public_path('FilesResponseBusiness/'),$name);
        }
        return response()->json(["message"=>"se guardo el archivo"]);;
    }
    public function uploadFileGeneralUA(Request $request,$id)
    {
        $contador = 0;
        $files = $request->file();
        foreach ($files as $file) {
            $filename = $file->getClientOriginalName();
        
            $filename= pathinfo($filename, PATHINFO_FILENAME);
            $name_File = str_replace(" ","_",$filename);
    
            $extension = $file->getClientOriginalExtension();
            $contador = $contador+1;
            $name = $id. "-" . $name_File . "." .$extension;
            $file->move(public_path('FilesResponseBusinessUA/'),$name);
        }
        return response()->json(["message"=>"se guardaron los archivos"]);
        //return response()->json(["messaje"=>"Archivos guardados ".$contador." id:".$id]);
       
    }
    /**
     * Devuelve todos los datos de una cotizacion
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($idCo,$idRe)
    {
        $quote = Quotation::select('id','offerValidity','deliveryTime','answerDate','paymentMethod','observation')
        ->where('id',$idCo)->get();
 
        $requestDetail = RequestDetail::select('id','amount','unitMeasure','description')
                                        ->where('request_quotitations_id',$idRe)->get();
        $quo = array();
        $quo = $quote;
        foreach ($requestDetail as $key => $detail)
        {
            $idDetail = $detail->id;
            
            $req = RequestDetail::select('request_details.id','request_details.amount'
             ,'request_details.unitMeasure','request_details.description','details.id as idDetail','details.unitPrice','details.totalPrice'
             ,'details.brand','details.industry','details.model','details.warrantyTime')
            ->join('details','request_details.id','=','details.request_details_id')
             ->where('request_details.id','=',$idDetail)
             ->where('details.quotations_id','=',$idCo)->get();
            $quo[] = $req;
        }
        $responseQuo = array();
        $responseQuo[] = $quo[0]; //siempre hace refencia no cambiara
        for ($i=0; $i < count($quo) ; $i++) { 
            if($i>0){
                $tamanio = count($quo[$i]);
                if($tamanio>0){
                    $responseQuo[] = $quo[$i];
                }
            }
        }
        return response()->json(['Cotizacion'=>$responseQuo], $this-> successStatus);
    }

    public function getQuotes($idReq)
    {
        //sacar nombres de empresa, numero de items cotizados, el total de todos los items cotizados
        $lista = array();
        $codesCompany = CompanyCode::where('request_quotitations_id',$idReq)->get();
        
        foreach($codesCompany as $key => $codeCompany)
        {
            $idCode = $codeCompany->id;
            $idQuotation = $codeCompany->idQuotation;
            $quotations = Quotation::where('company_codes_id',$idCode)->get();

            foreach($quotations as $key2 => $quotation)
            {       
                $idQuo = $quotation->id;
                $idEmpresa = $quotation->business_id;
                $empresa = Business::select('nameEmpresa')->where('id','=',$idEmpresa)->get();
                $res['Empresa'] = $empresa[0]->nameEmpresa;
                $prices = Detail::select('totalPrice')->where('quotations_id',$idQuo)->get();
                $nroDetails = count($prices);
                $totals = 0;

                foreach($prices as $key3 => $price)
                {
                    $total = $price->totalPrice;
                    $totals = $totals + $total;
                }
                $res['ItemsCotizados'] = $nroDetails;
                $res['TotalEnBs'] = $totals;
                $res['idCotizacion'] = $idQuotation;
                $res['id']=$idQuo;
                $lista[] = $res;
                    
            }
        }
        $printedQuotes = PrintedQuote::where('request_quotitations_id',$idReq)->get();
        
        foreach($printedQuotes as $key => $printed)
        {
            $idCode = $printed->id;
            $idQuotation = $printed->idQuotation;
            $quotations = Quotation::where('printed_quotes_id',$idCode)->get();

            foreach($quotations as $key2 => $quotation)
            {       
                $idQuo = $quotation->id;
                $idEmpresa = $quotation->business_id;
                $empresa = Business::select('nameEmpresa')->where('id','=',$idEmpresa)->get();
                $res['Empresa'] = $empresa[0]->nameEmpresa;
                $prices = Detail::select('totalPrice')->where('quotations_id',$idQuo)->get();
                $nroDetails = count($prices);
                $totals = 0;

                foreach($prices as $key3 => $price)
                {
                    $total = $price->totalPrice;
                    $totals = $totals + $total;
                }
                $res['ItemsCotizados'] = $nroDetails;
                $res['TotalEnBs'] = $totals;
                $res['idCotizacion'] = $idQuotation;
                $res['id']=$idQuo;
                $lista[] = $res;
                    
            }
        }

        return response()->json(['Cotizaciones'=>$lista], $this-> successStatus);

    }

    public function comparativeChart($idRe)
    {
       $chart = array();
       $list = array();
       $res = array();

       $codesCompany = CompanyCode::where('request_quotitations_id',$idRe)->get();
       $requesDetails = RequestDetail:: select('id','description','amount')->where('request_quotitations_id',$idRe)->get();
       
       foreach($requesDetails as $key1 => $reDetail)
       {
           $idDe = $reDetail->id;
          // $chart[] = $reDetail;
           $nombreEmpresas = array();
            foreach($codesCompany as $key => $codeCompany)
            {
                $idCode = $codeCompany->id; 
                $quotations = Quotation::where('company_codes_id',$idCode)->get();

                foreach($quotations as $key2 => $quotation)
                {
                    $idQuo = $quotation->id;
                    $idEmpresa = $quotation->business_id;
                    $empresa = Business::select('nameEmpresa')->where('id','=',$idEmpresa)->get();
                    $list['Empresa'] =$empresa[0]->nameEmpresa;
                    $nombreEmpresas[]= $empresa[0]->nameEmpresa;
                    $detail = Detail::select('unitPrice','totalPrice')->where('quotations_id',$idQuo)
                    ->where('request_details_id',$idDe)->get();
                    $existDetail = count ($detail);
                
                    if($existDetail > 0)
                    {
                        $detalle = $detail[0];
                        $totalPrice = $detalle['totalPrice'];
                        $list['total'] = $totalPrice;
                    }
                    else
                    {
                        $list['total'] = null;                        
                    }
                    
                    $chart[] = $list;
                }
                    
            }
            
            $reDetail['cotizaciones'] = $chart;
            $chart = null;
            $res[] = $reDetail;

        }    
        
        return response()->json(['comparativeChart'=>$res, "businesses"=>$nombreEmpresas], $this-> successStatus);

    }
    
    //muestra el archivo de un detalle de la cotizacion
    public function showFilesDetailsBusiness($namefile){
        $path = public_path('/FilesResponseBusiness\\'.$namefile);
        return response()->file($path);
    }

    /**
     * devuelve los nombres de archivos adjuntos al detalle de la cotizacion (empresa)
     * segun el id de una cotizacion
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showNameFilesDetailsBusiness($idDetailOffert)
    { 
        $directory = public_path().'/FilesResponseBusiness'; 
        $listDir = $this-> dirToArrayOffer($directory,$idDetailOffert);
        return response()->json($listDir,200);
    }
    //muestra el archivo de un detalle de la cotizacion
    public function showFileBusinessManualUA($namefile){
        $path = public_path('/FilesResponseBusinessUA\\'.$namefile);
        return response()->file($path);
    }

    /**
     * devuelve los nombres de archivos adjuntos al detalle de la cotizacion (empresa)
     * segun el id de una cotizacion
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showNameFileBusinessManualUA($idDetailOffert)
    { 
        $directory = public_path().'/FilesResponseBusinessUA'; 
        $listDir = $this-> dirToArrayOffer($directory,$idDetailOffert);
        return response()->json($listDir,200);
    }

     //devuelve un arreglo de archivos de un directorio determinado $dir
     public function dirToArrayOffer($dir,$idOffer) {
        $listDir = array();
        if($handler = opendir($dir)) {
            while (($file = readdir($handler)) !== FALSE) {
                $numberDigitosIdOffer = strlen($idOffer);
                $stringNumberOffert = substr($file,0,$numberDigitosIdOffer);
                //para ubicar los archivos que empiezen con el numero del detalle de la cotizacion 
                if ($file != "." && $file != ".." && $stringNumberOffert==$idOffer) {
                    if(is_file($dir."/".$file)) {
                        $listDir[] = $file;
                    }elseif(is_dir($dir."/".$file)){
                        $listDir[$file] = $this->dirToArray ($dir."/".$file);
                    }
                }
            }
            closedir($handler);
        }
        return $listDir;
    }

    /**
     * devuelve los codigos sin respuesta de las cotizaciones impresas 
     * segun el id de una solicitud de adquisicion
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showCodesQuotationUA($idRequest)
    { 
        $codesPrintedQuotes = PrintedQuote::select('id','idQuotation')
                                        ->where('request_quotitations_id',$idRequest)->get();
        $codesQuotesWithoutResponse = array();
        foreach ($codesPrintedQuotes as $key => $code){
            $idPrintedQuote = $code->id;
            $idQuotation = $code->idQuotation;
            $quotation = Quotation::where('printed_quotes_id',$idPrintedQuote)->get();
            $quotationFound = count($quotation);
            if($quotationFound==0){
                array_push($codesQuotesWithoutResponse,$idQuotation);
            }
        }                           ;
        return response()->json($codesQuotesWithoutResponse,200);
    }

    /**
     * devuelve los codigos sin respuesta de las cotizaciones impresas 
     * segun el id de una solicitud de adquisicion
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showCodeQuotation($id)
    {
        $idQuotation = CompanyCode::where('id', $id)
                                    ->pluck('idQuotation')->first();          
        return response()->json($idQuotation,200);
    }

    /**
     * devuelve true si la cotizacion almenos a sido enviada o impresa una vez,
     * caso contrario devuelve false
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function verifyRequestQuotation($idRequest)
    {
        $printedQuotes = PrintedQuote::where('request_quotitations_id',$idRequest)->first();
        $emailQuotes = CompanyCode::where('request_quotitations_id',$idRequest)->first();
        $verify = false;
        if($printedQuotes!=null || $emailQuotes!=null ){
            $verify= true;
        } 
        return response()->json(["response"=>$verify],200);
    }
    /**
     * devuelve los solicitudes de cotizacion respondidas y sin responder 
     * segun el id de una solicitud de adquisicion
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showAllQuotations($idRequest)
    {
        $printedQuotes = PrintedQuote::where('request_quotitations_id',$idRequest)->get();
        $emailQuotes = CompanyCode::where('request_quotitations_id',$idRequest)->get();
        $table = array();
        $resp = array();
        $quote_print=0;
        $quote_email=0;
        $quote_resp=0;
        $contpr = count($printedQuotes);
        $contem = count($emailQuotes);
        if($contpr>0){
            foreach ($printedQuotes as $kpq => $pq){
                $idPrintedQuote = $pq->id;
                $idQuotation = $pq->idQuotation;
                $quotation = Quotation::where('printed_quotes_id',$idPrintedQuote)->get();
                $valor = count($quotation);
                if($valor==1){
                    $quote=$quotation[0];
                    $answerDate = $quote->answerDate;
                    $idBusiness = $quote->business_id;
                    $nameBusiness = Business::where('id',$idBusiness)->pluck('nameEmpresa')->first();
                    $fila = ['idQuotation'=>$idQuotation,'business'=>$nameBusiness,'typeQuotation'=>'Impresa','answerDate'=>$answerDate];
                    array_push($table,$fila);
                    $quote_resp++;
                }
                else{
                    if($valor==0){
                        $emailBusiness = $pq->email;
                        $nameBusiness = Business::where('email',$emailBusiness)->pluck('nameEmpresa')->first();
                        $fila = ['idQuotation'=>$idQuotation,'business'=>$nameBusiness,'typeQuotation'=>'Impresa','answerDate'=>'Sin respuesta'];
                        array_push($table,$fila);
                    }
                }
                $quote_print++; 
            }
        }
        if($contem>0){
            foreach ($emailQuotes as $keq => $eq){
                $idEmailQuote = $eq->id;
                $idQuotation = $eq->idQuotation;
                $quotation = Quotation::where('company_codes_id',$idEmailQuote)->get();
                $valor = count($quotation);
                if($valor==1){
                    $quote=$quotation[0];
                    $answerDate = $quote->answerDate;
                    $idBusiness = $quote->business_id;
                    $nameBusiness = Business::where('id',$idBusiness)->pluck('nameEmpresa')->first();
                    $fila = ['idQuotation'=>$idQuotation,'business'=>$nameBusiness,'typeQuotation'=>'Correo','answerDate'=>$answerDate];
                    array_push($table,$fila);
                    $quote_resp++;
                }
                else{
                    if($valor==0){
                        $emailBusiness = $eq->email;
                        $nameBusiness = Business::where('email',$emailBusiness)->pluck('nameEmpresa')->first();
                        $fila = ['idQuotation'=>$idQuotation,'business'=>$nameBusiness,'typeQuotation'=>'Correo','answerDate'=>'Sin respuesta'];
                        array_push($table,$fila);
                    }
                }
                $quote_email++;
            }
        }
        $resp = ['quote_print'=>$quote_print,'quote_email'=>$quote_email, 'quote_resp'=>$quote_resp ,'table'=>$table];
                                  
        return response()->json($resp,200);
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
