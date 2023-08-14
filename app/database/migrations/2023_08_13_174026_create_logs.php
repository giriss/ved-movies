<?php

use Leaf\Database;
use Illuminate\Database\Schema\Blueprint;

class CreateLogs extends Database
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!static::$capsule::schema()->hasTable('logs')) :
            static::$capsule::schema()->create('logs', function (Blueprint $table) {
                $table->increments('id');
                $table->date('date');
                $table->string('api_key');
                $table->integer('count');
            });
        endif;

        // you can now build your migrations with schemas
        // Schema::build(static::$capsule, dirname(__DIR__) . '/Schema/logs.json');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        static::$capsule::schema()->dropIfExists('logs');
    }
}
