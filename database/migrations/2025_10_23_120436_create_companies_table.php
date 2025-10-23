<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->text('company_address');
            $table->string('contact_number');
            $table->string('email_address');
            $table->string('solicitor_name');
            $table->string('regulated_by');
            $table->string('company_reg_number');
            $table->string('company_logo')->nullable();
            $table->json('accreditor_logos')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
}