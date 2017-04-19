<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTennisReservationIdForeignKeyToPushNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('push_notifications', function ($table) {
            
             $table->bigInteger('tennis_reservation_id')->unsigned()->nullable();
             $table->foreign('tennis_reservation_id')->references('id')
                    ->on('tennis_reservation')
                    ->onDelete('cascade');
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('push_notifications', function ($table) {
            $table->dropForeign('push_notifications_tennis_reservation_id_foreign');
            $table->dropColumn('tennis_reservation_id');
           
        });
    }
}
