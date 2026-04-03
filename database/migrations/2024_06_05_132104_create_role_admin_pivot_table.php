<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleAdminPivotTable extends Migration
{
    public function up()
    {
        Schema::create('role_admin', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id');
            $table->foreign('admin_id', 'admin_id_fk_9827493')->references('id')->on('admins')->onDelete('cascade');
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id', 'role_id_fk_9827493')->references('id')->on('roles')->onDelete('cascade');
        });
    }
}
