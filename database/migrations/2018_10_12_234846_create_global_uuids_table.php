<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Grammars\MySqlGrammar as IlluminateMySqlGrammar;
use Illuminate\Database\Query\Grammars\SQLiteGrammar as IlluminateSQLiteGrammar;
use Submtd\LaravelGlobalUuid\Grammars\SQLiteGrammar;
use Submtd\LaravelGlobalUuid\Grammars\MySqlGrammar;

class CreateGlobalUuidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // set up grammars so we can have a real binary column
        $connection = app('db')->connection();
        $queryGrammar = $connection->getQueryGrammar();
        if (!in_array(get_class($queryGrammar), [
            IlluminateMySqlGrammar::class,
            IlluminateSQLiteGrammar::class,
        ])) {
            throw new \Exception("There current grammar doesn't support binary uuids. Only  MySql and SQLite connections are supported.");
        }
        if ($queryGrammar instanceof IlluminateSQLiteGrammar) {
            $grammar = new SQLiteGrammar();
        } else {
            $grammar = new MySqlGrammar();
        }
        $grammar->setTablePrefix($queryGrammar->getTablePrefix());
        $connection->setSchemaGrammar($grammar);
        // migrate table
        Schema::create('global_uuids', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->nullableMorphs('model');
            $table->unique(['model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('global_uuids');
    }
}
