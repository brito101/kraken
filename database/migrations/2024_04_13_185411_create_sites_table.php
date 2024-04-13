<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->ipAddress('ip')->nullable();
            $table->text('description')->nullable();
            $table->longText('technologies')->nullable();
            $table->longText('observations')->nullable();
            $table->string('status')->nullable();
            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->dateTime('last_check')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        DB::statement("
        CREATE OR REPLACE VIEW `sites_view` AS
        SELECT s.id, s.url, s.description, s.status, s.last_check
        FROM sites as s
        WHERE s.deleted_at IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW sites_view");
        Schema::dropIfExists('sites');
    }
};
