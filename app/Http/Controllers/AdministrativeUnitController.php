<?php

namespace App\Http\Controllers;

use App\AdministrativeUnit;
use App\Faculty;
use App\User;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class AdministrativeUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public $successStatus = 200;

    public function index()
    {
        $administrativeUnits = AdministrativeUnit::select("id","name","faculties_id")->get();
        foreach ($administrativeUnits as $key => $administrativeUnit) {
            $facultie_id =  $administrativeUnit['faculties_id'];
            //para mostrar en la columna Facultad de la lista de Unidades administrativas
            $faculty = Faculty::find($facultie_id);
            $nameFaculty = $faculty['nameFacultad'];
            $administrativeUnit['faculty'] = $nameFaculty;
            $users = User::select("id")->where('administrative_units_id', $administrativeUnit['id'])->get();
            $numUsers = count($users);
            $administrativeUnit['admin']=null;
            $userF['id'] = '';
            $userF['name'] = 'Seleccione';
            $userF['lastName'] = 'administrador';
            if($numUsers>0){
                $rolr = Role::where('nameRol','Jefe Administrativo')->get();
                $rol = $rolr[0];
                $usersd=$rol->users()->get();
                $numUsersd = count($usersd);
                foreach($usersd as $keyu => $user){
                    $id_us=$user['id'];
                    $userUnit=User::select("id","name","lastName")->where('administrative_units_id', $administrativeUnit['id'])->where('id',$id_us)->get();
                    $numUserUnit = count($userUnit);
                    if($numUserUnit==1){
                        $userN=$userUnit[0];
                        $administrativeUnit['admin']=$userN;
                    }
                    else{
                        if($administrativeUnit['admin']==null && $keyu==$numUsersd-1){
                            $administrativeUnit['admin'] = $userF;
                        }
                    }
                }/**foreach($users as $keyu => $user){
                    $roles = $user->roles()->get();
                    foreach($roles as $keyr => $rol){
                        $numRoles = count($roles);
                        if($rol['nameRol']=='Jefe Administrativo'){
                            $administrativeUnit['admin'] = $user;
                        }
                        else{
                            if($administrativeUnit['admin']==null && $keyr==$numRoles-1){
                                $administrativeUnit['admin'] = $userF;
                            }
                        }
                    }
                }*/
            }
            else{
                $administrativeUnit['admin'] = $userF;
            }
            $administrativeUnits[$key]=$administrativeUnit;           
        }
        return response()->json(['Administrative_unit'=>$administrativeUnits],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'faculties_id' => 'required', 
            
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $input = $request->all(); 
        $id_facultad = $input['faculties_id'];
        $facultad = Faculty::find($id_facultad);
        //no permite registrar mas de una unidad dentro de la misma facultad
        if($facultad['inUse'] ==1)
        {
              $message = 'La facultad '.$facultad['nameFacultad'].' ya tiene una unidad administrativa ';
              return response()->json(['message'=>$message], 200); 
        }
        $facultad['inUse']=1;
        $facultad->save();
        $id_user = $input['idUser'];
        if($id_user!=null){
         //si se le manda el id del usuario entonces registra a ese usuario como administrador de la unidad creada        if($tamInput==3){
               $requestAdminUnit = $request->only('name','faculties_id');
               $administrativeUnit = AdministrativeUnit::create($requestAdminUnit);
               $user2 = User::find($id_user);
               $user2['administrative_units_id'] = $administrativeUnit['id'];
               $user2->update();
               return response()->json(['message'=> "Registro exitoso"], $this-> successStatus); 
        }
        else{
                $administrativeUnit = AdministrativeUnit::create($input);
                return response()->json(['message'=>"Registro exitoso"], $this-> successStatus);
        }
    }

    public function assignHeadUnit($idU,$idA)
    {
        //$user = User::where('id',$idU)->get();
        //$countUsers = count($user);
//
        //$unitExist = AdministrativeUnit::where('id',$idA)->get();
        //$countUnits = count($unitExist);
//
        //if($countUsers > 0)
        //{
        //    if($countUnits > 0)
        //    {
        //        $user2 = User::find($idU);
        //        $user2['administrative_units_id'] = $idA;
        //        $user2->update();
        //        $message2 = 'Registro exitoso ';
        //        return response()->json(['message'=>$message2], 200);
        //    }
        //    else
        //    {
        //        $message = 'La unidad Administrativa con id'. $idA.' no existe  ';
        //        return response()->json(['message'=>$message], 200);
        //    }

        //}
        //else
        //{
        //    $message2 = 'El usuario con id'. $idU.' no existe  ';
        //    return response()->json(['message'=>$message2], 200);
        //}
        $user = User::find($idU);
        //$arregloRoles = $user->roles()->get();
        //foreach($arregloRoles as $kr => $rol){
        //    $namerol = $rol->nameRol;
        //    if($namerol=='Jefe unidad de gasto'){
        //        $rolestatus = $rol->pivot->role_status;
        //        $adminstatus = $rol->pivot->administrative_unit_status;
        //        $spenstatus = $rol->pivot->spending_unit_status;
        //        if($rolestatus==1 && $adminstatus==0 && $spenstatus==0){
        //            $resp2[] = $user;
        //        }
        //    }
        //dd($ar);
        //if(1==2){

        //}
        //else{
            $user->roles()->attach(1,['administrative_unit_id'=>$idA,'administrative_unit_status'=>1]);
            //$yt=$user->roles()->get();
            //$r=$yt[0];
            //$e = $r->where('pivot_id',19);
            $users = DB::table('role_user')
              ->where('id', 19)
              ->update(['updated_at' => now()]);
              //->touch();
              //->update(['administrative_unit_status' =>987]);
              //->save();
            //$e ->updateExistingPivot(1,['administrative_unit_status'=>111]);
            dd('eer');
            //dd($user->roles()->where('id',19)->get());

            //$user->roles()->updateExistingPivot(1,['administrative_unit_status'=>76])->where('id',19);
            //$user->update;
        //}
        //dd($are);
        return response()->json(['message'=>true], 200);
    }

    public function getAdmiUser($idUnit)
    {
        
        $admi = User::select('id','name','lastName')->where('administrative_units_id',$idUnit)->get();
        $admis = count($admi);
        if($admis>0)
        {
           return response()->json(["User"=> $admi],200);
        }
        else
        {
            $message = 'La unidad administrativa aun no cuenta con un administrador  ';
            return response()->json(['message'=>$message], 200);
        }
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
     * @param  \App\AdministrativeUnit  $administrativeUnit
     * @return \Illuminate\Http\Response
     */
    public function show(AdministrativeUnit $administrativeUnit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AdministrativeUnit  $administrativeUnit
     * @return \Illuminate\Http\Response
     */
    public function edit(AdministrativeUnit $administrativeUnit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AdministrativeUnit  $administrativeUnit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdministrativeUnit $administrativeUnit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AdministrativeUnit  $administrativeUnit
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdministrativeUnit $administrativeUnit)
    {
        //
    }
}
