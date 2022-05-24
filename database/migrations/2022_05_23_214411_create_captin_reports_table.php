<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaptinReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('captin_reports', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->unsignedBigInteger('captin')->nullable();
            $table->foreign('captin')->references('id')->on('captins')->onDelete('set null');
            $table->unsignedBigInteger('train')->nullable();
            $table->foreign('train')->references('id')->on('trains')->onDelete('set null');
            $table->unsignedBigInteger('report')->nullable();
            $table->foreign('report')->references('id')->on('reports')->onDelete('set null');
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
        Schema::dropIfExists('captin_reports');
    }
}
