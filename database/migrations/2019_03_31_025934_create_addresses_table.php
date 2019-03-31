<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('street')->nullable();
			$table->string('number')->nullable();
			$table->string('zip_code')->nullable();
			$table->string('neighborhood')->nullable();
			$table->string('complement')->nullable();
			$table->string('latitude')->nullable();
			$table->string('longitude')->nullable();
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
        Schema::dropIfExists('addresses');
    }
}
