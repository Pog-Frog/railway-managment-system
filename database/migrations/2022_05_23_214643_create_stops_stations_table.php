<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStopsStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stops_stations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('station')->nullable();
            $table->foreign('station')->references('id')->on('stations')->onDelete('set null');
            $table->unsignedBigInteger('line')->nullable();
            $table->foreign('line')->references('id')->on('lines')->onDelete('set null');
            $table->time('scheduled_departure_time');
            $table->time('scheduled_arrival_time');
            $table->date('date');
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
        Schema::dropIfExists('stops_stations');
    }
}