<?php

use Leaf\Database;
use Illuminate\Database\Schema\Blueprint;

class CreateMovies extends Database
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!static::$capsule::schema()->hasTable('movies')) :
            static::$capsule::schema()->create('movies', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->string('actors');
                $table->string('year');
                $table->string('description');
                $table->string('poster');
            });
        endif;

        // you can now build your migrations with schemas
        // Schema::build(static::$capsule, dirname(__DIR__) . '/Schema/movies.json');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        static::$capsule::schema()->dropIfExists('movies');
    }
}
