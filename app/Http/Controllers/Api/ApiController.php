<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use DateTime;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    const SUCCESS_STATUS = 1;
    const ERROR_STATUS = 0;

    const SUCCESS_MESSAGE = "Success";
    const ERROR_MESSAGE = "Error";


    public function statistics()
    {
        if(!isset(request()->start_date) || empty(request()->start_date) || !isset(request()->end_date) || empty(request()->end_date))
        {

            $status = ApiController::ERROR_STATUS;
            $message = ApiController::ERROR_MESSAGE . "\'start_date\' or \'end_date\' key is not set.";

            return [
                'status' => $status,
                'message' => $message,
                'data' => null
            ];
        }

        if($this->validateDate(request()->start_date) && $this->validateDate(request()->end_date)){
            $status = ApiController::ERROR_STATUS;
            $message = ApiController::ERROR_MESSAGE . "\'start_date\' or \'end_date\' is not valid.";

            return [
                'status' => $status,
                'message' => $message,
                'data' => null
            ];
        }

        $from_date = request()->start_date;
        $to_date = request()->end_date;

        $rejected = Ticket::where('status', 'Rejected')->whereBetween(DB::raw('DATE(updated_at)'), array($from_date, $to_date))->get()->count();

        $completed = Ticket::where('status', 'Completed')->whereBetween(DB::raw('DATE(updated_at)'), array($from_date, $to_date))->get()->count();


        $percentage = round(($completed / ($completed + $rejected)) * 100);
        $status = ApiController::SUCCESS_STATUS;
        $message = ApiController::SUCCESS_MESSAGE;

        return [
            'status' => $status,
            'message' => $message,
            'data' => [
                'completed' => $completed,
                'rejected'  => $rejected,
                'procentage' => $percentage
            ]
        ];
    }

    public function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}
