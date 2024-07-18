<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('admin_id')->nullable();
            $table->unsignedInteger('vendor_id')->nullable();
            $table->unsignedInteger('driver_id')->nullable();
            $table->unsignedInteger('hospital_id');
            $table->enum('status', ['requested', 'processing', 'not-found', 'confirmed', 'denied', 'order-placed', 'cancelled', 'success', 'delivery-started', 'delivered', 'collected'])->nullable()->default('requested');
            $table->string('patient_name')->nullable();
            $table->string('patient_age')->nullable();
            $table->string('doctor_name')->nullable();
            $table->string('doctor_nmc')->nullable();
            $table->string('address')->nullable();
            $table->text('latitude')->nullable();
            $table->text('longitude')->nullable();
            $table->string('delivery_area')->nullable();
            $table->text('nearest_landmark')->nullable();
            $table->text('additional_detail')->nullable();
            $table->text('remarks')->nullable();
            $table->string('preferred_date')->nullable();
            $table->string('preferred_time')->nullable();
            $table->string('alternate_name')->nullable();
            $table->string('alternate_phone')->nullable();
            $table->string('otp', 4)->nullable();
            $table->unsignedInteger('sub_total')->default(0);
            $table->unsignedInteger('vendor_total')->default(0);
            $table->unsignedInteger('outside_total')->default(0);
            $table->unsignedInteger('shipping_fee')->default(0);
            $table->unsignedInteger('total')->default(0);
            $table->unsignedInteger('paying_total')->default(0);
            $table->timestamps();
            $table->string('updated_by')->nullable();

            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prescriptions');
    }
}
