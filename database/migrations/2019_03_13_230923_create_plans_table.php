<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('plans');
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('longitude');
            $table->string('lattitude');
            $table->string('region');
            $table->string('adresse');
            $table->text('description');
            $table->integer('rate');
            $table->integer('approvedBy');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
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
       // Schema::dropIfExists('plans');
       // $table->dropForeign('lists_user_id_foreign');
       // $table->dropIndex('lists_user_id_index');
       // $table->dropColumn('user_id');
        Schema::table('plans', function(Blueprint $table)
        {
            Schema::dropIfExists('plans');
            $table->dropForeign('user_id'); //
        });
    }
}
