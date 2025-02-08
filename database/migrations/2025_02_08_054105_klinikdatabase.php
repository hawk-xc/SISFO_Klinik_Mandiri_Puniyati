<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // ER Diagram
    // https://drive.google.com/file/d/107LkN1xItz2Cj76FzJm9RgFEpABDAqtB/view?usp=sharing
    public function up(): void
    {
        Schema::create('tb_pasien', function(Blueprint $table) {
            $table->id();
            $table->string('no_rm')->unique()->default(Str::uuid());
            $table->string('nama_pasien');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir');
            $table->string('alamat');
            $table->string('riwayat_penyakit');
            $table->string('no_telp');
            $table->timestamps();
        });

        Schema::create('tb_obat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_obat');
            $table->string('jenis');
            $table->integer('stok')->default(0);
            $table->integer('harga')->default(0);
            $table->timestamps();
        });

        Schema::create('tb_pelayanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelayanan');
            $table->integer('biaya')->default(0);
            $table->timestamps();
        });

        Schema::create('tb_bidan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bidan');
            $table->string('spesialisasi');
            $table->string('no_telp');
            // 'jadwal_praktek' => json_encode([
            //     'hari' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
            //     'jam' => ['mulai' => '09:00', 'selesai' => '15:00']
            // ])
            $table->json('jadwal_praktek');
            $table->timestamps();
        });

        Schema::create('tb_pemeriksaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_obat')->references('id')->on('tb_obat');
            $table->string('tindakan');
            $table->string('diagnosis');
            $table->string('keluhan');
            $table->timestamps();
        });

        Schema::create('tb_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pasien')->references('id')->on('tb_pasien');
            $table->foreignId('id_pemeriksaan')->references('id')->on('tb_pemeriksaan');
            $table->foreignId('id_bidan')->references('id')->on('tb_bidan');
            $table->foreignId('id_pelayanan')->references('id')->on('tb_pelayanan');
            $table->enum('status', ['menunggu', 'selesai', 'terjadwal'])->default('selesai');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_pasien');
        Schema::dropIfExists('tb_obat');
        Schema::dropIfExists('tb_pelayanan');
        Schema::dropIfExists('tb_bidan');
        Schema::dropIfExists('tb_pemeriksaan');
        Schema::dropIfExists('tb_pendaftaran');
    }
};
