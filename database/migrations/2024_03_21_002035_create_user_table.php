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

            $table->string('username', 100)
                ->comment('Fill with name of user');
            $table->string('email', 50)
                ->comment('Fill with user email for login');
            $table->string('password', 255)
                ->comment('Fill with user password');

            $table->timestamp('updated_security')
                ->comment('Fill with timestamp when user update password / email')
                ->nullable();
            $table->timestamps();
            // $table->softDeletes();

            $table->index('email');
            $table->index('username');
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
