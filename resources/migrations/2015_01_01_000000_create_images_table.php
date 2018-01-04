<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Create Images Table
 *
 * @category Migrations
 * @package  HustleWorks\ChuteLaravel
 * @author   Don Herre <don@hustleworks.com>
 * @license  Proprietary and confidential
 * @link     http://hustleworks.com
 */
class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->nullableMorphs('imageable');
            $table->string('disk');
            $table->string('status');
            $table->string('path');
            $table->string('uuid');
            $table->string('filename');
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->integer('size')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('extension')->nullable();
            $table->string('alt')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();

            $table->unique(['imageable_type', 'imageable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('images');
    }
}