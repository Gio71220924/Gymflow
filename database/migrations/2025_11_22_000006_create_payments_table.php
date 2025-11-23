<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_id');
            $table->decimal('amount', 12, 2);
            $table->enum('method', ['cash', 'transfer', 'ewallet', 'credit_card']);
            $table->timestamp('paid_at')->nullable();
            $table->enum('status', ['berhasil', 'pending', 'gagal'])->default('pending');
            $table->string('bukti_bayar', 255)->nullable();
            $table->string('reference_no', 100)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->index('invoice_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
