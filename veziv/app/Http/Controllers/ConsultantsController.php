<?php

namespace App\Http\Controllers;
use App\Models\Consultant;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ConsultantsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function showAllConsultants(Request $request)
    {
        $arr = array("Consultants" => Consultant::all(), "user" => Auth::user());
        
        foreach($arr['Consultants'] as $val){
            $results = DB::table('appointments')
                ->whereRaw('consultant_id = ?', [$val['id']])
                ->get();
            
            foreach($results as $res){
                $creatorUser = DB::table('users')
                    ->whereRaw('id = ?', [$res->user_id])
                    ->get();

                $res->username = $creatorUser[0]->name;
                
            }
            
            $val['appointments'] = $results;      
        }
        return $arr;
    }

    public function AddNewConsultant(Request $request)
    {
        $payload = $request;
        $res = Consultant::create([
            'firstName' => $payload['firstname'],
            'lastName' => $payload['lastname']
            ]);
        return $res;
    }

    public function DeleteAppointment(Request $request)
    {
        $payload = $request;
        $res = DB::table('appointments')
                ->where('id', $payload['appointment_id'])
                ->delete();
        return $res;
    }

    public function DeleteConsultant(Request $request)
    {
        $payload = $request;
        $res = DB::table('consultants')
                ->where('id', $payload['consultant_id'])
                ->delete();
        return $res;
    }

    private static function timeInInterval($strtime){
        $times = explode(":", $strtime);
        $time = intval($times[0]) * 60 + intval($times[1]);

        # if appointment is between 9:00 and 13:00
        if($time >= 9 * 60 && $time <= (13-1) * 60){
           return True; 
        }

        # if appointment is between 15:30 and 21:00
        if($time >= (15 * 60 + 30) && $time <= (21 - 1) * 60){
            return True;
        }

    }

    private static function dateInInterval($strtime){
        $weekday = date('N', strtotime($strtime));

        if ($weekday >= 1 && $weekday <= 5){
            return True;
        }

        return False;
    }

    public function CreateNewAppointment(Request $request)
    {
        $call_result = array();
        $payload = $request;
        $timestamp = $payload['date'] . ' ' . $payload['time'];

        $time = strtotime($timestamp);
        $results = DB::table('appointments')
                ->whereRaw('date(timestamp) = ? and consultant_id = ?', [$payload['date'], $payload['consultant_id']])
                ->get();
        

        if(ConsultantsController::dateInInterval($payload['date'])==False){
            $call_result['error'] = "Appointments can only be made from Monday to Friday.";
        }  
        
        if(ConsultantsController::timeInInterval($payload['time'])==False){
            $call_result['error'] = "Appointments can only be made between 9:00-13:00 and 15:30 and 21:00.";
        } 

        if(!array_key_exists('error', $call_result)){
            foreach($results as $result){
                
                $unix_timestamp = strtotime($result->timestamp);
                $diff = abs($unix_timestamp - $time) / 60;
                if($diff < 90){
                    $call_result['error'] = "This appointment time overlaps with other appointments";
                    break;
                }
            }
        }

        if(!array_key_exists('error', $call_result)){
            $res = Appointment::create([
            'user_id' => $payload['user_id'],
            'consultant_id' => $payload['consultant_id'],
            'timestamp' => $timestamp
            ]);
        }

        return $call_result;
    }

}
