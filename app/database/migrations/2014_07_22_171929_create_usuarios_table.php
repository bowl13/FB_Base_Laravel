<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsuariosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('usuarios', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('fbuid', 20);
			$table->string('rut', 20);
			$table->string('firstname', 100);
			$table->string('lastname', 100);
            $table->string('genero', 20);
            $table->string('phone', 20);
            $table->string('address', 200);
            $table->string('email', 200);
            $table->integer('comuna_id')->unsigned();
            $table->string('ip', 20);
            $table->char('complete', 1);
            $table->text('meta', 20);
            $table->text('access_token', 20);
            $table->text('expire_token', 20);
			$table->timestamps();

            #Indices
            $table->index('comuna_id');
            #FK
            if (Schema::hasTable('comunas'))
            {
                $table->foreign('comuna_id')->references('id')->on('comunas');
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
		Schema::drop('usuarios');
	}

}
