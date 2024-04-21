<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
class PatientDetailsController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $totalPatientsToday = DB::table('patient_details')
        ->whereDate('created_at', $today)
        ->count();
        $totalPatients = DB::table('patient_details')
        ->count();
        try {
            // Retrieve patient details from the database
            $patientDetails = DB::table('patient_details')->get();


            // Return the patient details as JSON response
            return response()->json([
                'total_patients_today' => $totalPatientsToday,
                'patient_details' => $patientDetails,
                'total_patients' =>$totalPatients,
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions or errors
            return response()->json(['error' => 'Failed to fetch patient details.'], 500);
        }
    }
    public function save(Request $request)
    {

        // Define the table name where you want to save the data
        $tableName = 'patient_details';

        // Define the list of valid columns for the table
        $validColumns = ['pat_lastname', 'pat_firstname', 'pat_middlename', 'pat_suffixname', 'pat_birth', 'pat_address'];

        // Extract the request data
        $requestData = $request->all();

        // Prepare the data to be inserted into the database
        $dataToInsert = [];
        foreach ($requestData as $key => $value) {
            // Check if the key is a valid column for the table
            if (in_array($key, $validColumns)) {
                // Add the key-value pair to the data array
                $dataToInsert[$key] = $value;
            }
        }

        try {
            // Insert the data into the database
            DB::table($tableName)->insert($dataToInsert);

            // Return a response indicating success or any other relevant information
            return response()->json(['message' => 'Data saved successfully']);
        } catch (\Illuminate\Database\QueryException $exception) {
            // Handle any database errors, such as column mismatches
            return response()->json(['message' => 'Failed to save data. Database error: ' . $exception->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            // Retrieve the patient ID from the request
            $patientId = $request->input('pat_id');

            // Retrieve the updated patient data from the request
            $updatedData = $request->except('pat_id'); // Exclude the ID from the updated data

            // Perform the update using the Query Builder
            DB::table('patient_details')->where('pat_id', $patientId)->update($updatedData);

            return response()->json(['message' => 'Patient updated successfully']);
        } catch (\Exception $exception) {
            // Handle any errors
            return response()->json(['message' => 'Failed to update patient', 'error' => $exception->getMessage()], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            // Retrieve the patient ID from the request data
            $patientId = $request->input('patientId');

            // Perform the deletion using the Query Builder
            DB::table('patient_details')->where('pat_id', $patientId)->delete();

            return response()->json(['message' => 'Patient deleted successfully']);
        } catch (\Exception $exception) {
            // Handle any errors
            return response()->json(['message' => 'Failed to delete patient', 'error' => $exception->getMessage()], 500);
        }
    }
}
