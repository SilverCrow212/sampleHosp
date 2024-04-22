<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
class DashboardController extends Controller
{
    public function headersPatients(Request $request)
    {
        try {
            // Retrieve patient details from the database
            $today = Carbon::today()->toDateString();
            $totalPatientsToday = DB::table('patient_details')
            ->whereDate('created_at', $today)
            ->count();
            $totalPatients = DB::table('patient_details')
            ->count();

            return response()->json([
                'total_patients_today' => $totalPatientsToday,
                'total_patients' =>$totalPatients,
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions or errors
            return response()->json(['error' => 'Failed to fetch patient details.'], 500);
        }
    }


    public function headersAdmitted(Request $request)
    {
        $today = Carbon::today()->toDateString();

        $totalAdmittedToday = DB::table('admission_details')
        ->whereDate('created_at', $today)
        ->count();

        $totalAdmitted = DB::table('admission_details')
        ->count();

        $totalActive = DB::table('admission_details')
        ->whereNull('discharge')
        ->count();

        $totalPatientsDischargedToday = DB::table('admission_details')
        ->whereDate('discharge', $today)
        ->count();

        return response()->json([
            'total_admitted_today' => $totalAdmittedToday,
            'total_admitted' => $totalAdmitted,
            'active_admitted' => $totalActive,
            'discharged_today'=>$totalPatientsDischargedToday
        ]);
    }


}
