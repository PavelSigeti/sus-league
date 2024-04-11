<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->dateTime('register_start');
            $table->dateTime('register_end');
            $table->dateTime('race_start');
            $table->string('title');
            $table->enum('status', ['active', 'default', 'group', 'fleet', 'finished'])->default('active');
            $table->text('excerpt')->nullable();
            $table->text('description')->nullable();
            $table->integer('race_amount_drop')->default(1);
            $table->integer('race_amount_group_drop')->default(1);
            $table->integer('race_amount_fleet_drop')->default(1);
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
        Schema::dropIfExists('stages');
    }
};
