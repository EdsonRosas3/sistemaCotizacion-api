<?php

use Illuminate\Database\Seeder;
use App\CompanyCode;
class CompanyCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $CompanyCode = new CompanyCode();
        $CompanyCode->code="adcde";
        $CompanyCode->idQuotation=1;
        $CompanyCode->email="smartcube@gmail.com";
        $CompanyCode->request_quotitations_id=1;
        $CompanyCode->save();

        $CompanyCode2 = new CompanyCode();
        $CompanyCode2->code="qwert";
        $CompanyCode2->idQuotation=2;
        $CompanyCode2->email="softComputers@gmail.com";
        $CompanyCode2->request_quotitations_id=3;
        $CompanyCode2->save();

        $CompanyCode3 = new CompanyCode();
        $CompanyCode3->code="asdfg";
        $CompanyCode3->idQuotation=6;
        $CompanyCode3->email="shoppingpc@gmail.com";
        $CompanyCode3->request_quotitations_id=1;
        $CompanyCode3->save();
    }
}
