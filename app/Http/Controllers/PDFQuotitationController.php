<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\RequestQuotitation; 
use App\RequestDetail;
use App\AdministrativeUnit;
use App\Faculty;
use App\PrintedQuote;
use App\CompanyCode;
use App\Business;
use Barryvdh\DomPDF\Facade as PDF;
//use PDF;
class PDFQuotitationController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function requestquotitationPDFV2($id , $bussines)
    {

        
        $empresaFind = Business::find($bussines);
        $idAdministrative = RequestQuotitation::where('id',$id)->pluck('administrative_unit_id')->first();
        $idFacultad = AdministrativeUnit::where('id',$idAdministrative)->pluck('faculties_id')->first();
        $facultad = Faculty::find($idFacultad);
        $detailsQuotitations = RequestDetail::where("request_quotitations_id",$id)->get();
        $details = array();
        foreach ($detailsQuotitations as $key => $detail) {
            array_push($details,$detail);
        }

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
        $empresa = "";
        $email="";
        if($bussines!=0){
            $empresa=$empresaFind->nameEmpresa;
            $email =$empresaFind->email;
        }
        //enviar datos al pdf
        if($empresaFind!=null){
            $requestPrintedQuote = ['idQuotation'=>$codQuotation,'email'=>$email,'request_quotitations_id'=>$id];
            $printedQuote = PrintedQuote::create($requestPrintedQuote);
            $data=[
                'details'=>$details,
                'facultad'=>$facultad,
                'codigo'=>$codQuotation,
                'empresa'=>$empresa
            ];
            $pdf = PDF::loadView('quotitationv2',$data);
        }
        else{
            $requestPrintedQuote = ['idQuotation'=>$codQuotation,'request_quotitations_id'=>$id];
            $printedQuote = PrintedQuote::create($requestPrintedQuote);
            $data=[
                'details'=>$details,
                'facultad'=>$facultad,
                'codigo'=>$codQuotation,
                'empresa'=>""
            ];
            $pdf = PDF::loadView('quotitationv2',$data);
        }
        return $pdf->setPaper('a4', 'landscape')->stream('quotitation.pdf');
    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function requestquotitationPDF(Request $request)
    {
        //recibe:
        //request_quotitations_id
        //business (nombre de la empresa)
        //email
        $id = $request->only('request_quotitations_id');
        $idAdministrative = RequestQuotitation::where('id',$id)->pluck('administrative_unit_id')->first();
        $idFacultad = AdministrativeUnit::where('id',$idAdministrative)->pluck('faculties_id')->first();
        $facultad = Faculty::find($idFacultad);
        $detailsQuotitations = RequestDetail::where("request_quotitations_id",$id)->get();
        $details = array();
        foreach ($detailsQuotitations as $key => $detail) {
            array_push($details,$detail);
        }

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
        $empresa=$request->business;
        $email =$request->email;
        //enviar datos al pdf
        if($empresa!=null){
            $requestPrintedQuote = ['idQuotation'=>$codQuotation,'email'=>$email,'request_quotitations_id'=>$request->request_quotitations_id];
            $printedQuote = PrintedQuote::create($requestPrintedQuote);
            $data=[
                'details'=>$details,
                'facultad'=>$facultad,
                'codigo'=>$codQuotation,
                'empresa'=>$empresa
            ];
            $pdf = PDF::loadView('quotitationv2',$data);
        }
        else{
            $requestPrintedQuote = ['idQuotation'=>$codQuotation,'request_quotitations_id'=>$request->request_quotitations_id];
            $printedQuote = PrintedQuote::create($requestPrintedQuote);
            $data=[
                'details'=>$details,
                'facultad'=>$facultad,
                'codigo'=>$codQuotation,
                'empresa'=>""
            ];
            $pdf = PDF::loadView('quotitationv2',$data);
        }
        return $pdf->setPaper('a4', 'landscape')->stream('quotitation.pdf');
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
