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
        DB::statement("
        CREATE OR REPLACE VIEW `sites_view` AS
        SELECT s.id, s.url, s.description, s.status, s.last_check, COUNT(l.id) as links
        FROM sites as s
        LEFT JOIN links l ON l.site_id = s.id AND l.deleted_at IS NULL
        WHERE s.deleted_at IS NULL
        GROUP BY s.id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
        CREATE OR REPLACE VIEW `sites_view` AS
        SELECT s.id, s.url, s.description, s.status, s.last_check
        FROM sites as s
        WHERE s.deleted_at IS NULL
        ");
    }
};
