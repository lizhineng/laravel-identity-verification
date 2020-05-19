<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdentityVerificationsTable extends Migration
{
    public function up()
    {
        Schema::create('identity_verifications', function (Blueprint $table) {
            $table->id();
            $table->morphs('auth');
            $table->string('name');
            $table->string('id_number');
            $table->string('portrait_path');
            $table->string('id_card_portrait_path')->nullable();
            $table->string('id_card_emblem_path')->nullable();
            $table->json('id_card')->nullable();
            $table->unsignedSmallInteger('status');
            $table->unsignedInteger('authority_comparision_score');
            $table->unsignedInteger('portrait_comparision_score');
            $table->unsignedInteger('id_card_portrait_comparision_score');
            $table->timestamps();

            $table->index('status');
        });
    }
}