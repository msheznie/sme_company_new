<?php

use App\Models\Navigation;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleHasPermissions;
use App\Services\GeneralService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;

class CreateNavigations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigations', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('parent_id')->index()->nullable();
            $table->foreign('parent_id')->references('id')->on('navigations');

            $table->string('name')->unique();
            $table->string('path')->nullable();
            $table->string('icon')->nullable();
            $table->integer('order_index');
            $table->integer('has_children');
            $table->integer('status');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('navigations')->truncate();
        Navigation::insert([
            [
                'parent_id' => null,
                'name' => 'Dashboard',
                'icon' => 'home',
                'path' => '/dashboard',
                'order_index' => 1,
                'has_children' => 0,
                'status' => 1,
            ],
        ]);

        GeneralService::navigationInsert();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('navigations');
    }
}
