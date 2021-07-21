<?php

use Illuminate\Database\Seeder;
use App\Quotation;

class QuotationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Quotation = new Quotation();
        $Quotation->offerValidity="2021-06-27";
        $Quotation->deliveryTime="2021-06-27";
        $Quotation->answerDate="2021-06-13";
        $Quotation->paymentMethod="efectivo";
        $Quotation->observation="Esto es una observacion";
        $Quotation->company_codes_id=1;
        $Quotation->business_id=1;
        $Quotation->save();

        $Quotation = new Quotation();
        $Quotation->offerValidity="2021-06-28";
        $Quotation->deliveryTime="2021-06-28";
        $Quotation->answerDate="2021-06-14";
        $Quotation->paymentMethod="efectivo";
        $Quotation->observation="Esto es una observacion dos";
        $Quotation->company_codes_id=2;
        $Quotation->business_id=2;
        $Quotation->save();
    }
}
