<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager', function (Blueprint $table) {
            // 递增的 ID (主键)
            $table->increments('id');
            // 用户名，长度20,varchar，不能为null
            $table->string('username', 20)->notNull();
            // 密码，varchar（255）,不能为null
            $table->string('password')->notNull();
            // 性别，1=男，2=女，3=保密，默认男
            $table->enum('gender', [1, 2, 3])->notNull()->default('1');
            // 手机号varchar（11）
            $table->string('mobile', 11);
            // 电子邮箱地址archar(40)
            $table->string('email', 50);
            // 角色表中的主键id,tinyint
            $table->tinyInteger('role_id');
            // 创建时间dateTime,更新时间dateTime,系统自己创建
            $table->timestamps();
            // 记录登录功能需要存储的标记varchar(100)
            $table->rememberToken();
            // 账号状态1=禁用，2=启用默认启用
            $table->enum('status', [1, 2])->notNull()->default('2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager');
    }
}
