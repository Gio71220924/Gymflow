<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('member_membership_id');
            $table->string('nomor_invoice', 50)->unique();
            $table->date('due_date')->nullable();
            $table->decimal('total_tagihan', 12, 2);
            $table->decimal('diskon', 12, 2)->default(0);
            $table->decimal('pajak', 12, 2)->default(0);
            $table->enum('status', ['draft', 'menunggu', 'lunas', 'batal'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('member_membership_id')->references('id')->on('member_memberships')->onDelete('cascade');
            $table->index('member_membership_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
