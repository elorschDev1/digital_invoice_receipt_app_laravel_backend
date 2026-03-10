<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void{
        Schema::table("registered_users_clients",function(Blueprint $table){
            $table->index("business_email");
        });
        
    }
    public function down(): void{
       Schema::table("registered_users_clients",function(Blueprint $table){
        $table->dropIndex(["business_email"]);

       });
    }
};
