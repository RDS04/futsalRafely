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
        // Tambah field region dan admin_id ke tabel lapangans
        Schema::table('lapangans', function (Blueprint $table) {
            if (!Schema::hasColumn('lapangans', 'region')) {
                $table->enum('region', ['padang', 'bukittinggi', 'sijunjung'])->default('padang')->after('gambar');
            }
            if (!Schema::hasColumn('lapangans', 'admin_id')) {
                $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('cascade')->after('region');
            }
        });

        // Tambah field region dan admin_id ke tabel sliders
        Schema::table('sliders', function (Blueprint $table) {
            if (!Schema::hasColumn('sliders', 'region')) {
                $table->enum('region', ['padang', 'bukittinggi', 'sijunjung'])->default('padang')->after('gambar');
            }
            if (!Schema::hasColumn('sliders', 'admin_id')) {
                $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('cascade')->after('region');
            }
        });

        // Tambah field region dan admin_id ke tabel events
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'region')) {
                $table->enum('region', ['padang', 'bukittinggi', 'sijunjung'])->default('padang')->after('gambar');
            }
            if (!Schema::hasColumn('events', 'admin_id')) {
                $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('cascade')->after('region');
            }
        });

        // Tambah field region dan customer_id, lapangan_id ke tabel bokings
        Schema::table('bokings', function (Blueprint $table) {
            if (!Schema::hasColumn('bokings', 'region')) {
                $table->enum('region', ['padang', 'bukittinggi', 'sijunjung'])->default('padang')->after('catatan');
            }
            if (!Schema::hasColumn('bokings', 'customer_id')) {
                $table->foreignId('customer_id')->nullable()->constrained('costumers')->onDelete('cascade')->after('region');
            }
            if (!Schema::hasColumn('bokings', 'lapangan_id')) {
                $table->foreignId('lapangan_id')->nullable()->constrained('lapangans')->onDelete('cascade')->after('customer_id');
            }
            if (!Schema::hasColumn('bokings', 'total_harga')) {
                $table->decimal('total_harga', 10, 2)->nullable()->after('lapangan_id');
            }
            if (!Schema::hasColumn('bokings', 'status')) {
                $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending')->after('total_harga');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lapangans', function (Blueprint $table) {
            $table->dropForeignKeyIfExists('lapangans_admin_id_foreign');
            $table->dropColumnIfExists(['region', 'admin_id']);
        });

        Schema::table('sliders', function (Blueprint $table) {
            $table->dropForeignKeyIfExists('sliders_admin_id_foreign');
            $table->dropColumnIfExists(['region', 'admin_id']);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropForeignKeyIfExists('events_admin_id_foreign');
            $table->dropColumnIfExists(['region', 'admin_id']);
        });

        Schema::table('bokings', function (Blueprint $table) {
            $table->dropForeignKeyIfExists('bokings_customer_id_foreign');
            $table->dropForeignKeyIfExists('bokings_lapangan_id_foreign');
            $table->dropColumnIfExists(['region', 'customer_id', 'lapangan_id', 'total_harga', 'status']);
        });
    }
};
