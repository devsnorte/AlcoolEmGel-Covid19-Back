<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('user')->unique();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('alcool_em_gel')->nullable();
            $table->string('alcool_liquido')->nullable();
            $table->string('mascara')->nullable();
            $table->string('luva')->nullable();
            $table->string('aberto')->nullable();
            $table->string('obeservacao')->nullable();
            $table->string('url')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('type')->default("0"); // 0 = Usuário comum, 1 = Admin
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
