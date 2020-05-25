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
            $table->uuid('uuid');
            $table->morphs('auth');
            $table->string('scene');
            $table->string('name');
            $table->string('id_number');
            $table->string('portrait_path');
            $table->string('id_card_portrait_path')->nullable();
            $table->string('id_card_emblem_path')->nullable();
            $table->json('id_card')->nullable();
            $table->unsignedSmallInteger('status');
            $table->unsignedSmallInteger('verify_status')->nullable();
            $table->unsignedInteger('authority_comparision_score')->nullable();
            $table->unsignedInteger('portrait_comparision_score')->nullable();
            $table->unsignedInteger('id_card_portrait_comparision_score')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index('scene');
            $table->index('status');
        });
    }
}