<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Create Image Transformations Table
 *
 * @category Migrations
 * @package  HustleWorks\ChuteLaravel
 * @author   Don Herre <don@hustleworks.com>
 * @license  Proprietary and confidential
 * @link     http://hustleworks.com
 */
class CreateImageTransformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_transformations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('image_id')->unsigned();
            $table->string('name');
            $table->string('filename');
            $table->string('disk');
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('quality')->nullable();
            $table->integer('size')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('image_id')
                ->references('id')
                ->on('images')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('image_transformations');
    }
}