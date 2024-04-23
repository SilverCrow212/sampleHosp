const savepatient = async()=>{
    try {
        const response = await axios.post('api/save-patient',patientDetails.value);
        fetchDashboardData();
        console.log(response);
        display.value = false;
    } catch (error) {
        fetchDashboardData();
        console.error('Error:', error);
        display.value = false;
    }
};

const updatePatient = async () => {
    try {
        const response = await axios.put('/api/update-patient', patientToEdit);
        fetchAdmittedDashboardData();
        fetchDashboardData();
        editDialogVisible.value=false;
    } catch (error) {
        fetchAdmittedDashboardData();
        fetchDashboardData();
        console.error('Error:', error);
        editDialogVisible.value=false;
        
    }
};

const deletePatient = async () => {
    try {
        const response = await axios.delete('api/delete-patient', {
            data: { patientId: patientIdToDelete }
        });
        fetchDashboardData();
        console.log(response);
        deleteDialogVisible.value=false;
    } catch (error) {
        fetchDashboardData();
        console.error('Error:', error);
        deleteDialogVisible.value=false;
    }
};

const admitPatient = async () =>{
    console.log('testdate and time',patientAdmission.value);
    try {
        const response = await axios.post('api/admit-patient',patientAdmission.value);
        fetchAdmittedDashboardData();
        console.log(response);
        submitted.value=false;
        admitDialogVisible.value = false;
    } catch (error) {
        fetchAdmittedDashboardData();
        console.error('Error:', error);
        submitted.value=true
        admitDialogVisible.value = true;
    }
};