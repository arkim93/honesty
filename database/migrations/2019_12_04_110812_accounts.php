<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Accounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('account', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('currency', 5)->default('IDR');
            $table->enum('currency_placement', ['before', 'after'])->default('before');
            $table->decimal('balance', 13)->default(0);
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
         Schema::dropIfExists('accounts');
    }
}
