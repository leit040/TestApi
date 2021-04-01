<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projectables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id');
            $table->foreignId('projectable_id');
            $table->foreignId('projectable_type');
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
        Schema::disableForeignKeyConstraints('projectables');
        Schema::dropIfExists('projectables');
    }
}
