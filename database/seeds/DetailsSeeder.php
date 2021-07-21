<?php

use Illuminate\Database\Seeder;
use App\Detail;

class DetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Detail = new Detail();
        $Detail->unitPrice=2569;
        $Detail->totalPrice=2569*3;
        $Detail->brand="na";
        $Detail->industry="italiano";
        $Detail->model="na";
        $Detail->warrantyTime="200 dias";
        $Detail->request_details_id=1;
        $Detail->quotations_id=1;
        $Detail->save();

        $Detail = new Detail();
        $Detail->unitPrice=250;
        $Detail->totalPrice=250*3;
        $Detail->brand="na";
        $Detail->industry="brasilero";
        $Detail->model="na";
        $Detail->warrantyTime="20 dias";
        $Detail->request_details_id=2;
        $Detail->quotations_id=1;
        $Detail->save();

        $Detail = new Detail();
        $Detail->unitPrice=25;
        $Detail->totalPrice=25*3;
        $Detail->brand="na";
        $Detail->industry="chino";
        $Detail->model="na";
        $Detail->warrantyTime="2 dias";
        $Detail->request_details_id=3;
        $Detail->quotations_id=1;
        $Detail->save();

        $Detail = new Detail();
        $Detail->unitPrice=3000;
        $Detail->totalPrice=3000*3;
        $Detail->brand="na";
        $Detail->industry="japones";
        $Detail->model="na";
        $Detail->warrantyTime="25 dias";
        $Detail->request_details_id=4;
        $Detail->quotations_id=1;
        $Detail->save();

        $Detail = new Detail();
        $Detail->unitPrice=3000;
        $Detail->totalPrice=3000*3;
        $Detail->brand="na";
        $Detail->industry="italiano";
        $Detail->model="na";
        $Detail->warrantyTime="200 dias";
        $Detail->request_details_id=1;
        $Detail->quotations_id=2;
        $Detail->save();

        $Detail = new Detail();
        $Detail->unitPrice=30;
        $Detail->totalPrice=30*3;
        $Detail->brand="na";
        $Detail->industry="chino";
        $Detail->model="na";
        $Detail->warrantyTime="2 dias";
        $Detail->request_details_id=3;
        $Detail->quotations_id=2;
        $Detail->save();
    }
}
