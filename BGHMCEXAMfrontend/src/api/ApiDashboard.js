const fetchDashboardData = async () => {
    try {
      const response = await axios.get('/api/all-patients');
      dashboardData.value = response.data.patient_details;
      dashboardToday.value= response.data.total_patients_today;
      dashboardTotal.value=response.data.total_patients;
      // console.log('Today',dashboardToday.value);
    } catch (error) {
      console.error('Error fetching all patient data:', error);
    }
  };
  