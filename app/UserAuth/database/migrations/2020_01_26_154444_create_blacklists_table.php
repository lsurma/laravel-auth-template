<?php

use App\UserAuth\Models\Blacklist;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlacklistsTable extends Migration
{
    protected string $table;

    public function __construct()
    {
        $this->table = (new Blacklist())->getTable();    
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('type')->nullable(false);
            $table->string('value')->nullable(false);

            $table->unique(['type', 'value']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
