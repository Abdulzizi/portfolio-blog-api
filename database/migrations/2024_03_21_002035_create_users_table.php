<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_roles_id')->comment('Fill with id from table users_roles');
            $table->string('name', 100)
                ->comment('Fill with name of user');
            $table->string('email', 50)
                ->comment('Fill with user email for login');
            $table->string('password', 255)
                ->comment('Fill with user password');
            $table->string('phone_number', 25)
                ->default(null)
                ->comment('Fill with phone number of user')
                ->nullable();
            $table->string('photo', 100)
                ->comment('Fill with user profile picture')
                ->nullable();
            $table->timestamp('updated_security')
                ->comment('Fill with timestamp when user update password / email')
                ->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->integer('deleted_by')->default(0);

            $table->index('user_roles_id');
            $table->index('email');
            $table->index('name');
            $table->index('updated_security');
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
};
