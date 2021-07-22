<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_codes', function (Blueprint $table) {
            $table->id();
            $table->integer('idQuotation');
            $table->string("code");
            $table->string("email");
            $table->integer('status')->default(0);
            $table->foreignId('request_quotitations_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_codes');
    }
}
