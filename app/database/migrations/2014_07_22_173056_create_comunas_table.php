<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateComunasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comunas', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('name', 64);
            $table->integer('region_id')->unsigned();
			$table->timestamps();

            #Indices
            $table->index('region_id');
            #FK
            if (Schema::hasTable('regiones'))
            {
                $table->foreign('region_id')->references('id')->on('regiones');
            }
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('comunas');
	}

}
