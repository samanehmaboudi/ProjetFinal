<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bouteille_catalogue', function (Blueprint $table) {
            $table->id();
            $table->string('code_saQ', 20)->unique()->nullable();
            $table->string('nom', 255);
            $table->foreignId('id_type_vin')->nullable()->constrained('type_vin')->nullOnDelete();
            $table->foreignId('id_pays')->nullable()->constrained('pays')->nullOnDelete();
            $table->string('region', 100)->nullable();
            $table->year('millesime')->nullable();
            $table->decimal('prix', 10, 2);
            $table->string('url_image', 255)->nullable();
            $table->string('volume', 20)->default('750 ml');
            $table->dateTime('date_import')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bouteille_catalogue');
    }
};

