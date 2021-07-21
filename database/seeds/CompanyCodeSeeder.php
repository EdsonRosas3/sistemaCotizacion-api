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
        $CompanyCode->email="smartcube@gmail.com";
        $CompanyCode->request_quotitations_id=1;
        $CompanyCode->save();

        $CompanyCode2 = new CompanyCode();
        $CompanyCode2->code="qwert";
        $CompanyCode2->email="softComputers@gmail.com";
        $CompanyCode2->request_quotitations_id=1;
        $CompanyCode2->save();

        $CompanyCode3 = new CompanyCode();
        $CompanyCode3->code="asdfg";
        $CompanyCode3->email="shoppingpc@gmail.com";
        $CompanyCode3->request_quotitations_id=1;
        $CompanyCode3->save();
    }
}
