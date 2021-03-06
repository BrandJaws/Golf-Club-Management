<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //START: Create compound_reservations  view
        //returns a view that has reservations grouped by time slots and reservation players aggregated
        
        $query  = " CREATE VIEW compound_reservations AS "; 
        $query .= "     SELECT ";
        $query .= "     course.club_id as club_id, ";
        $query .= "     course.id as course_id, ";
        $query .= "     course.name as course_name, ";
        $query .= "     course.bookingDuration , ";
        $query .= "     routine_reservations.id as reservation_id, ";
        $query .= "     routine_reservations.game_status, ";
        $query .= "     reservation_time_slots.reservation_type as reservation_type, ";
        $query .= "     reservation_players.parent_id, ";
        $query .= "     reservation_time_slots.time_start as date_time_start, ";
        $query .= "     TIME(reservation_time_slots.time_start) as time_start, ";
        $query .= "     DATE(reservation_time_slots.time_start) as reserved_at, ";
        $query .= "     reservation_players.id as reservation_player_id, ";
        $query .= "     member.id as member_id, ";
        $query .= "     CONCAT_WS(' ', member.firstName, member.lastName)as member_name, ";
        $query .= "     reservation_players.reservation_status, ";
        $query .= "     reservation_players.comingOnTime, ";
        $query .= "     reservation_players.process_type ";
        $query .= "     FROM ";
        $query .= "     routine_reservations ";
        $query .= "     LEFT JOIN course ON routine_reservations.course_id = course.id ";
        $query .= "     LEFT JOIN reservation_time_slots ON reservation_time_slots.reservation_id = routine_reservations.id AND reservation_time_slots.reservation_type = '".addslashes("App\\Http\\Models\\RoutineReservation")."' ";
        $query .= "     LEFT JOIN reservation_players ON reservation_players.reservation_id = routine_reservations.id AND reservation_players.reservation_type = '".addslashes("App\\Http\\Models\\RoutineReservation")."' ";
        $query .= "     LEFT JOIN member ON reservation_players.member_id = member.id ";
        $query .= "     WHERE ";
        $query .= "     ((reservation_players.reservation_status ='RESERVED' AND reservation_players.response_status ='CONFIRMED') OR  reservation_players.reservation_status ='PENDING RESERVED') ";
        //Add other reservation types as and when created here with a UNION ALL clause
        
        DB::statement($query);
        //END: Create compound_reservations view
       
        //START: Create compound_reservations_aggregated  view
        //returns a view that has reservations grouped by time slots and reservation players aggregated
        
        $query  = " CREATE VIEW compound_reservations_aggregated AS "; 
        $query .= "     SELECT "; 
        $query .= "     course.club_id as club_id, ";
        $query .= "     course.id as course_id, ";
        $query .= "     course.name as course_name, ";
        $query .= "     routine_reservations.id as reservation_id, ";
        $query .= "     reservation_time_slots.reservation_type as reservation_type, ";
        $query .= "     GROUP_CONCAT(IFNULL(reservation_players.parent_id,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as parent_ids, ";
        $query .= "     reservation_time_slots.time_start as date_time_start, ";
        $query .= "     TIME(reservation_time_slots.time_start) as time_start, ";
        $query .= "     DATE(reservation_time_slots.time_start) as reserved_at, ";
        $query .= "     GROUP_CONCAT(IFNULL(reservation_players.id,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as reservation_player_ids, ";
        $query .= "     GROUP_CONCAT(IFNULL(member.id,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as member_ids, ";
        $query .= "     GROUP_CONCAT(IF(CONCAT_WS(' ', member.firstName, member.lastName) <> ' ',CONCAT_WS(' ', member.firstName, member.lastName),'Guest') ORDER BY reservation_players.id ASC SEPARATOR '||-separation-player-||' ) as member_names, ";
        $query .= "     GROUP_CONCAT(IFNULL( member.profilePic,' ') ORDER BY reservation_players.id ASC SEPARATOR '||-separation-player-||' ) as member_profile_pics, ";
        $query .= "     GROUP_CONCAT(IFNULL(reservation_players.reservation_status,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as reservation_statuses, ";
        $query .= "     GROUP_CONCAT(IFNULL(reservation_players.response_status,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as response_statuses, ";
        $query .= "     GROUP_CONCAT(IFNULL(reservation_players.comingOnTime,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as comingOnTime_responses, ";
        $query .= "     GROUP_CONCAT(IFNULL(reservation_players.process_type,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as processTypes, ";
        $query .= "     routine_reservations.game_status, ";
        $query .= "     GROUP_CONCAT(IF(checkins_for_clubEntry.action IS NULL , 0 , 1) ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as club_entries, ";
        $query .= "     GROUP_CONCAT(IF(checkins_for_gameEntry.action IS NULL , 0 , 1) ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as game_entries ";
        $query .= "     FROM ";
        $query .= "     routine_reservations ";
        $query .= "     LEFT JOIN course ON routine_reservations.course_id = course.id ";
        $query .= "     LEFT JOIN reservation_time_slots ON reservation_time_slots.reservation_id = routine_reservations.id AND reservation_time_slots.reservation_type = '".addslashes("App\\Http\\Models\\RoutineReservation")."' ";
        $query .= "     LEFT JOIN reservation_players ON reservation_players.reservation_id = routine_reservations.id AND reservation_players.reservation_type = '".addslashes("App\\Http\\Models\\RoutineReservation")."' ";
        $query .= "     LEFT JOIN member ON reservation_players.member_id = member.id ";
        $query .= "     LEFT JOIN checkins as checkins_for_clubEntry ON checkins_for_clubEntry.action = 'CLUB ENTRY' AND checkins_for_clubEntry.member_id = member.id AND checkins_for_clubEntry.reservation_id = routine_reservations.id AND checkins_for_clubEntry.reservation_type = '".addslashes("App\\Http\\Models\\RoutineReservation")."' ";
        $query .= "     LEFT JOIN checkins as checkins_for_gameEntry ON checkins_for_gameEntry.action = 'GAME ENTRY' AND checkins_for_gameEntry.member_id = member.id AND checkins_for_gameEntry.reservation_id = routine_reservations.id AND checkins_for_gameEntry.reservation_type = '".addslashes("App\\Http\\Models\\RoutineReservation")."' ";
        $query .= "     WHERE ";
        $query .= "     ((reservation_players.reservation_status ='RESERVED' AND reservation_players.response_status ='CONFIRMED') OR  reservation_players.reservation_status ='PENDING RESERVED') ";
        $query .= "     GROUP BY course.id,course.club_id,course.name,routine_reservations.id,reservation_time_slots.time_start,reservation_time_slots.reservation_type";
        //Add other reservation types as and when created here with a UNION ALL clause
     
        DB::statement($query);
        //END: Create compound_reservations_aggregated view
        
        //START: Create reservations_by_timeslots  view
        //returns a view that has reservations for all courses grouped by time slots and reservation players aggregated
        
        $query  = " CREATE VIEW reservations_by_timeslots AS "; 
        $query .= "     SELECT "; 
        $query .= "     course.club_id as club_id, ";
        $query .= "     course.id as course_id, ";
        $query .= "     course.name as course_name, ";
        $query .= "     routine_reservations.id as reservation_id, ";
        $query .= "     reservation_time_slots.reservation_type as reservation_type, ";
        $query .= "     reservation_time_slots.time_start as time_start ";
        //$query .= "     routine_reservations.status ";
        $query .= "     FROM ";
        $query .= "     routine_reservations ";
        $query .= "     LEFT JOIN course ON routine_reservations.course_id = course.id ";
        $query .= "     LEFT JOIN reservation_time_slots ON reservation_time_slots.reservation_id = routine_reservations.id AND reservation_time_slots.reservation_type = '".addslashes("App\\Http\\Models\\RoutineReservation")."' ";
       
        //Add other reservation types as and when created here with a UNION ALL clause
        
        DB::statement($query);
        //END: Create reservations_by_timeslots view
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS compound_reservations');
        DB::statement('DROP VIEW IF EXISTS compound_reservations_aggregated');
        DB::statement('DROP VIEW IF EXISTS reservations_by_timeslots');
    }
}
