<?php

use Illuminate\Database\Seeder;
use App\Faculty;
class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faculty= new Faculty();
        $faculty->nameFacultad = "Ciencias y tecnología";
        $faculty->inUse = 1;
        $faculty->save();

        $faculty1= new Faculty();
        $faculty1->nameFacultad = "Derecho";
        $faculty1->save();

        $faculty2= new Faculty();
        $faculty2->nameFacultad = "Ciencias económicas";
        $faculty2->save();

        $faculty3= new Faculty();
        $faculty3->nameFacultad = "Humanidades";
        $faculty3->save();

        $faculty3= new Faculty();
        $faculty3->nameFacultad = "Arquitectura";
        $faculty3->save();
    }
}
