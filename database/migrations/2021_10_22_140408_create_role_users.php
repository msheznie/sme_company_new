<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRoleUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_users', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->nullableTimestamps();
            $table->engine = 'InnoDB';
            $table->primary(['user_id', 'role_id']);
        });

        DB::table('role_users')->insert([
            'user_id' => 1,
            'role_id' => 1,
        ]);

        $userRole = array('Super Admin','Admin','Module User','Supplier');
        foreach($userRole as $val){
            Role::create(['name' => $val]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_users');
    }
}
