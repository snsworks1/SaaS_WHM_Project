<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_xx_xx_add_marketing_opt_in_to_users_table.php

public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->boolean('marketing_opt_in')->default(false);
        $table->timestamp('marketing_opt_in_at')->nullable();
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['marketing_opt_in', 'marketing_opt_in_at']);
    });
}

};
