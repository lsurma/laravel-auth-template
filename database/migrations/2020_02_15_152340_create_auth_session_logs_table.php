<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthSessionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_session_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Morph relationship
            $table->string('authenticatable_type');
            $table->unsignedBigInteger('authenticatable_id');

            $table->string('guard');

            $table->string('session_id');
            
            $table->boolean('is_logged_out')->default(false);

            $table->timestamp('last_activity_at')->nullable()->default(null);

            $table->timestamps();

            $table->index(['authenticatable_type', 'authenticatable_id', 'guard', 'session_id'], 'authenticatable_guard_session');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth_session_logs');
    }
}
