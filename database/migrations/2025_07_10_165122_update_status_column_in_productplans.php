<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    DB::statement("ALTER TABLE product_plans MODIFY COLUMN status ENUM('Active', 'Inactive') DEFAULT 'Active'");
}

public function down()
{
    DB::statement("ALTER TABLE product_plans MODIFY COLUMN status ENUM('Pending', 'Completed') DEFAULT 'Pending'");
}
};
