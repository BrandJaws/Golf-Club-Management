<?php

namespace App\Jobs;

use App\Http\Controllers\Mobile\ReservationsController;
use App\Http\Models\ReservationPlayer;
use App\Http\Models\RoutineReservation;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MakeReservationPlayerDecision implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $reservationPlayerId;
    private $reservationSpecificJobNumber;
    private $finalCycleInitialization;


    public function __construct($reservationPlayerId,
                                $reservationSpecificJobNumber,
                                $finalCycleInitialization = false
    )
    {

        $this->reservationPlayerId = $reservationPlayerId;
        $this->reservationSpecificJobNumber = $reservationSpecificJobNumber;
        $this->finalCycleInitialization = $finalCycleInitialization;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        /*
         * Fetch reservationPlayer from db based on the id referenced in the job and
         *
         */

        $reservationPlayer = ReservationPlayer::find($this->reservationPlayerId);

        if($reservationPlayer){
            //if final cycle initialization, initiate the process and return
            if($this->finalCycleInitialization){
                $reservationPlayer->initiateFinalProcessForReservation();

                return;
            }

            /**
             *
             *Take a decision on a reservationPlayer If:
             * 1. reservationPlayer status is still pending or dropped
             * 2.nextJobToProcess number field on the reservationPlayer record matches the reservationSpecificJobNumber on this job
             */
          
            if($reservationPlayer->nextJobToProcess == $this->reservationSpecificJobNumber){
                if($reservationPlayer->response_status == \Config::get ( 'global.reservation.pending' ) ||
                   $reservationPlayer->response_status == \Config::get ( 'global.reservation.dropped' )){

                            $reservationsController = new ReservationsController();
                            echo $reservationsController->declineReservationRequest($this->reservationPlayerId);

                }
            }else{
                echo "Skipped Job";
            }
        }else{
            echo "Skipped Job";
        }


    }
}
