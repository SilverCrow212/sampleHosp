const dischargeUpdate = async () => {
    try {
        const response = await axios.put('/api/discharge-patient', patientAdmission.value);
        fetchAdmittedDashboardData();
        submittedDischarge.value=false;
        dischargeDialogVisible.value=false;
    } catch (error) {
        fetchAdmittedDashboardData();
        submittedDischarge.value=true;
        dischargeDialogVisible.value=true;
        console.error('Error:', error);
    }
};