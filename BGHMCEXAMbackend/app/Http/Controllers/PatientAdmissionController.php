<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
class PatientAdmissionController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today()->toDateString();
        try {
            // Retrieve patient details from the database
            $patientDetails = DB::table('admission_details')
            ->select('admission_details.*', 'patient_details.*') // Select all columns from both tables
            ->join('patient_details', 'admission_details.pat_id', '=', 'patient_details.pat_id') // Join based on pat_id
            ->whereNull('admission_details.discharge') // Filter where discharge is null
            ->get();

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
            // Return the patient details as JSON response
            return response()->json([
                'total_admitted_today' => $totalAdmittedToday,
                'patient_details' => $patientDetails,
                'total_admitted' => $totalAdmitted,
                'active_admitted' => $totalActive,
                'discharged_today'=>$totalPatientsDischargedToday
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions or errors
            return response()->json(['error' => 'Failed to fetch patient details.'], 500);
        }
    }
    public function save(Request $request)
    {
        // Define the table name where you want to save the data
        $tableName = 'admission_details';

        // Define the list of valid columns for the table
        $validColumns = ['pat_id','enccode', 'ward', 'admission', 'discharge'];

        try {
            // Extract the request data
            $requestData = $request->all();
            $pat_lastname = $requestData['pat_lastname'] ?? null;
            $pat_firstname = $requestData['pat_firstname'] ?? null;
            $pat_birth = $requestData['pat_birth'] ?? null;
            $admission = $requestData['admission'] ?? null;
            $ward = $requestData['ward'] ?? null;

            // Generate unique text using md5 hash
            $uniqueText = md5($pat_lastname . $pat_firstname . $pat_birth . $admission . $ward);

            // Assign uniqueText to the enccode key in requestData
            $requestData['enccode'] = $uniqueText;

            // Prepare the data to be inserted into the database
            $dataToInsert = [];
            foreach ($requestData as $key => $value) {
                // Check if the key is a valid column for the table
                if (in_array($key, $validColumns)) {
                    // Add the key-value pair to the data array
                    $dataToInsert[$key] = $value;
                }
            }

            // Insert the data into the database
            DB::table($tableName)->insert($dataToInsert);

            // Return a response indicating success
            return response()->json(['message' => 'Data saved successfully']);
        } catch (\Illuminate\Database\QueryException $exception) {
            // Handle any database errors, such as column mismatches
            return response()->json(['message' => 'Failed to save data. Database error: ' . $exception->getMessage()], 500);
        } catch (\Exception $exception) {
            // Handle any other exceptions
            return response()->json(['message' => 'Failed to save data. Error: ' . $exception->getMessage()], 500);
        }
    }
    public function updatedischarge(Request $request)
    {
        try {
            // Retrieve the patient ID from the request
            $patientId = $request->input('enccode');

            // Retrieve the updated patient data from the request
            $updatedData = $request->only('discharge'); // Include only the discharge column for update

            // Perform the update using the Query Builder
            DB::table('admission_details')->where('enccode', $patientId)->update($updatedData);

            return response()->json(['message' => 'Patient discharge updated successfully']);
        } catch (\Exception $exception) {
            // Handle any errors
            return response()->json(['message' => 'Failed to update patient discharge', 'error' => $exception->getMessage()], 500);
        }
    }


    // public function update(Request $request)
    // {
    //     try {
    //         // Retrieve the patient ID from the request
    //         $patientId = $request->input('pat_id');

    //         // Retrieve the updated patient data from the request
    //         $updatedData = $request->except('pat_id'); // Exclude the ID from the updated data

    //         // Perform the update using the Query Builder
    //         DB::table('patient_details')->where('pat_id', $patientId)->update($updatedData);

    //         return response()->json(['message' => 'Patient updated successfully']);
    //     } catch (\Exception $exception) {
    //         // Handle any errors
    //         return response()->json(['message' => 'Failed to update patient', 'error' => $exception->getMessage()], 500);
    //     }
    // }

    // public function delete(Request $request)
    // {
    //     try {
    //         // Retrieve the patient ID from the request data
    //         $patientId = $request->input('patientId');

    //         // Perform the deletion using the Query Builder
    //         DB::table('patient_details')->where('pat_id', $patientId)->delete();

    //         return response()->json(['message' => 'Patient deleted successfully']);
    //     } catch (\Exception $exception) {
    //         // Handle any errors
    //         return response()->json(['message' => 'Failed to delete patient', 'error' => $exception->getMessage()], 500);
    //     }
    // }
}
