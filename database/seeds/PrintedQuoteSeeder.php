<?php

use Illuminate\Database\Seeder;
use App\PrintedQuote;
class PrintedQuoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $CompanyCode = new PrintedQuote();
        $CompanyCode->idQuotation=3;
        $CompanyCode->email="smartcube@gmail.com";
        $CompanyCode->request_quotitations_id=1;
        $CompanyCode->save();

        $CompanyCode2 = new PrintedQuote();
        $CompanyCode2->idQuotation=4;
        $CompanyCode2->email="libreriajosue19@gmail.com";
        $CompanyCode2->request_quotitations_id=2;
        $CompanyCode2->save();

        $CompanyCode = new PrintedQuote();
        $CompanyCode->idQuotation=5;
        $CompanyCode->email="smartcube@gmail.com";
        $CompanyCode->request_quotitations_id=1;
        $CompanyCode->save();

    }
}
