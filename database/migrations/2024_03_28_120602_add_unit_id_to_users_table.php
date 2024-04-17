<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pastikan kolom belum ada sebelum mencoba menambahkannya
        if (!Schema::connection('pgsql_second')->hasColumn('users', 'unit_id')) {
            Schema::connection('pgsql_second')->table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('unit_id')->nullable()->after('email_verified_at');
                // Tambahkan indeks atau kunci asing jika diperlukan
                // $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus kolom jika ada
        if (Schema::connection('pgsql_second')->hasColumn('users', 'unit_id')) {
            Schema::connection('pgsql_second')->table('users', function (Blueprint $table) {
                $table->dropColumn('unit_id');
            });
        }
    }
};
