<script setup>
import { onMounted, reactive, ref, watch,onBeforeMount  } from 'vue';
import { ProductService } from '@/service/ProductService';
import { useLayout } from '@/layout/composables/layout';
import axios from 'axios';

const { isDarkTheme } = useLayout();


const dashboardData=ref();
const dashboardToday=ref();
const dashboardTotal=ref();

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


const currentlyAdmitted=ref();
const admittedToday=ref();
const admittedTotal=ref();
const admittedActive=ref();
const dischargedToday=ref();
const fetchAdmittedDashboardData = async () => {
  try {
    // Make an API call to fetch dashboard data
    const response = await axios.get('/api/admitted-patients');
    currentlyAdmitted.value = response.data.patient_details;
    admittedToday.value=response.data.total_admitted_today;
    admittedTotal.value=response.data.total_admitted;
    admittedActive.value=response.data.active_admitted;
    dischargedToday.value=response.data.discharged_today;
    

    console.log(currentlyAdmitted.value);
  } catch (error) {
    console.error('Error fetching all patient data:', error);
  }
};

onBeforeMount(() => {
    fetchDashboardData();
    fetchAdmittedDashboardData();
});

const lineOptions = ref(null);


const applyLightTheme = () => {
    lineOptions.value = {
        plugins: {
            legend: {
                labels: {
                    color: '#495057'
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: '#495057'
                },
                grid: {
                    color: '#ebedef'
                }
            },
            y: {
                ticks: {
                    color: '#495057'
                },
                grid: {
                    color: '#ebedef'
                }
            }
        }
    };
};

const applyDarkTheme = () => {
    lineOptions.value = {
        plugins: {
            legend: {
                labels: {
                    color: '#ebedef'
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: '#ebedef'
                },
                grid: {
                    color: 'rgba(160, 167, 181, .3)'
                }
            },
            y: {
                ticks: {
                    color: '#ebedef'
                },
                grid: {
                    color: 'rgba(160, 167, 181, .3)'
                }
            }
        }
    };
};

watch(
    isDarkTheme,
    (val) => {
        if (val) {
            applyDarkTheme();
        } else {
            applyLightTheme();
        }
    },
    { immediate: true }
);

const ward=['A','B','C','D'];

const display = ref(false);

const open =  () => {
    display.value = true;
};
const close = () => {
    display.value = false;
    deleteDialogVisible.value=false;
    editDialogVisible.value=false;
    admitDialogVisible.value=false;
    dischargeDialogVisible.value=false;

};
const patientDetails= ref(
    {
        pat_lastname:null,
        pat_firstname:null,
        pat_middlename:null,
        pat_suffixname:null,
        pat_birth:null,
        pat_address:null,
    }
);
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

//edit
let patientToEdit = null;
const editDialogVisible = ref(false); 
const openEditDialog = (patientId) => {
    patientToEdit = patientId;
    editDialogVisible.value = true;
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


//delete
let patientIdToDelete = null;
const deleteDialogVisible = ref(false); 
const openDeleteDialog = (patientId) => {
    patientIdToDelete = patientId;
    deleteDialogVisible.value = true;
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

//admit patient
const admitDialogVisible = ref(false); 
const openAdmitDialog = (patientId) => {
    patientToEdit = patientId;
    patientAdmission.value.pat_id=patientId.pat_id
    admitDialogVisible.value = true;
};

const patientAdmission= ref(
    {
        pat_id:null,
        enccode:null,
        ward:null,
        admission:null,
        discharge:null,
    }
);
const submitted=ref(false)
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

//discharge
const dischargeDialogVisible = ref(false); 
const openDischargeDialog = (patientId) => {
    patientToEdit = patientId;
    patientAdmission.value=patientId;
    // patientAdmission.value.enccode = patientId.enccode;
    dischargeDialogVisible.value = true;
};

const submittedDischarge=ref()
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
</script>

<template>
    <div class="grid">
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Total Patients</span>
                        <div class="text-900 font-medium text-xl">{{ dashboardTotal }}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-blue-100 border-round" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-ellipsis-h text-blue-500 text-xl"></i>
                    </div>
                </div>
                <span class="text-green-500 font-medium">{{dashboardToday}} new </span>
                <span class="text-500">since today</span>
            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Total Admitted Patients</span>
                        <div class="text-900 font-medium text-xl">{{ admittedTotal }}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-orange-100 border-round" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-map-marker text-orange-500 text-xl"></i>
                    </div>
                </div>
                <span class="text-green-500 font-medium">{{admittedToday}} new </span>
                <span class="text-500">since today</span>
            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Active Admitted Patients</span>
                        <div class="text-900 font-medium text-xl">{{admittedActive}}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-cyan-100 border-round" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-check text-cyan-500 text-xl"></i>
                    </div>
                </div>
                <span class="text-green-500 font-medium">{{ dischargedToday }} </span>
                <span class="text-500"> discharged today</span>
            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0" @click="open">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Add New Patient</span>
                        <span class="text-green-500 font-medium">Add Patient <i class="pi pi-plus-circle"></i></span>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-green-100 border-round" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-user-plus text-green-500 text-xl"></i>
                    </div>
                </div>
                <span class="text-green-500 font-medium">&nbsp; </span>
                <span class="text-500">&nbsp;</span>
            </div>
        </div>

<!-- input patient -->
        <Dialog header="New Patient" v-model:visible="display" :breakpoints="{ '960px': '75vw' }" :style="{ width: '30vw' }" :modal="true">
            <div class="card">
                <h5>Patient Details</h5>
                <div class="p-fluid formgrid grid">
                    <div class="field col-12 md:col-4">
                        <label for="firstname2">First Name</label>
                        <InputText v-model="patientDetails.pat_firstname" id="firstname2" type="text" />
                    </div>
                    <div class="field col-12 md:col-4">
                        <label for="middlename">Middle Name</label>
                        <InputText v-model="patientDetails.pat_middlename" id="middlename" type="text" />
                    </div>
                    <div class="field col-12 md:col-4">
                        <label for="lastname">Last Name</label>
                        <InputText v-model="patientDetails.pat_lastname" id="lastname" type="text" />
                    </div>
                    <div class="field col-12 md:col-4">
                        <label for="suffixname">Suffix name</label>
                        <InputText v-model="patientDetails.pat_suffixname" id="suffixname" type="text" />
                    </div>
                    <div class="field col-12 md:col-4">
                        <label for="birthdate">Birthdate</label>
                        <Calendar v-model="patientDetails.pat_birth" showIcon :showOnFocus="false" />
                    </div>{{ patientDetails }}
                    <div class="field col-12">
                        <label for="address">Address</label>
                        <Textarea v-model="patientDetails.pat_address" id="address" rows="4" />
                    </div>
                </div>
                
            </div>

            <template #footer>
                <Button label="Cancel" @click="close" icon="pi pi-times" class="p-button-outlined" style="color:red" />
                <Button label="Save" @click="savepatient" icon="pi pi-check" class="p-button-outlined" />
            </template>
        </Dialog>
<!-- input patient -->

        <div class="col-12 xl:col-6">
            <div class="card">
                <h5>Patient List</h5>
                <DataTable :value="dashboardData" :rows="5" :paginator="true" responsiveLayout="scroll">
                    <Column field="pat_name" header="ID Number" :sortable="true" style="width: 35%">
                        <template #body="slotProps">
                            {{ slotProps.data.pat_id }}
                        </template>
                    </Column>
                    <Column field="pat_name" header="Name" :sortable="true" style="width: 35%">
                        <template #body="slotProps">
                            {{ slotProps.data.pat_firstname }}, {{ slotProps.data.pat_lastname }} {{ slotProps.data.pat_middlename }} {{ slotProps.data.pat_suffixname }}
                        </template>
                    </Column>
                    <Column field="pat_birth" header="Birthdate" :sortable="true" style="width: 35%">
                        <template #body="slotProps">
                            {{ slotProps.data.pat_birth }}
                        </template>
                    </Column>
                    <Column field="pat_birth" header="Birthdate" :sortable="true" style="width: 35%">
                        <template #body="slotProps">
                            {{ slotProps.data.pat_address }}
                        </template>
                    </Column>
                    <Column style="width: 15%">
                    <template #header> Options </template>
                    <template #body="slotProps">
                        <div class="button-container">
                            <Button icon="pi pi-trash" type="button" class="p-button-text" title="Delete Patient" @click="openDeleteDialog(slotProps.data.pat_id)"></Button>
                            <Button icon="pi pi-user-edit" type="button" class="p-button-text" @click="openEditDialog(slotProps.data)" title="Edit Patient"></Button>
                            <Button icon="pi pi-sign-in" type="button" @click="openAdmitDialog(slotProps.data)" class="p-button-text" title="Admit Patient"></Button>
                        </div>
                    </template>
                    </Column> 
                </DataTable>
            </div>



<!-- edit patient dialog -->
        <Dialog header="Edit Patient" v-model:visible="editDialogVisible" :breakpoints="{ '960px': '75vw' }" :style="{ width: '30vw' }" :modal="true">
            <div class="card">
                <h5>Patient Details</h5>
                <div class="p-fluid formgrid grid">
                    <div class="field col-12 md:col-4">
                        <label for="firstname2">First Name</label>
                        <InputText v-model="patientToEdit.pat_firstname" id="firstname2" type="text" />
                    </div>
                    <div class="field col-12 md:col-4">
                        <label for="middlename">Middle Name</label>
                        <InputText v-model="patientToEdit.pat_middlename" id="middlename" type="text" />
                    </div>
                    <div class="field col-12 md:col-4">
                        <label for="lastname">Last Name</label>
                        <InputText v-model="patientToEdit.pat_lastname" id="lastname" type="text" />
                    </div>
                    <div class="field col-12 md:col-4">
                        <label for="suffixname">Suffix name</label>
                        <InputText v-model="patientToEdit.pat_suffixname" id="suffixname" type="text" />
                    </div>
                    <div class="field col-12 md:col-4">
                        <label for="birthdate">Birthdate</label>
                        <Calendar v-model="patientToEdit.pat_birth" showIcon :showOnFocus="false" />
                    </div>
                    <div class="field col-12">
                        <label for="address">Address</label>
                        <Textarea v-model="patientToEdit.pat_address" id="address" rows="4" />
                    </div>
                </div>
                
            </div>

            <template #footer>
                <Button label="Cancel" @click="close" icon="pi pi-times" class="p-button-outlined" style="color:red" />
                <Button label="Save" @click="updatePatient" icon="pi pi-check" class="p-button-outlined" />
            </template>
        </Dialog>

<!-- edit patient dialog -->
<!-- delete patient dialog -->
        <Dialog header="Delete Patient" v-model:visible="deleteDialogVisible" :breakpoints="{ '960px': '75vw' }" :style="{ width: '30vw' }" :modal="true">
            <div class="card">
                <h1>Are you sure you want to delete this patient?</h1>
            </div>

            <template #footer>
                <Button label="Cancel" @click="close" icon="pi pi-times" class="p-button-outlined" style="color:red" />
                <Button label="Yes" @click="deletePatient" icon="pi pi-check" class="p-button-outlined" />
            </template>
        </Dialog>
<!-- delete patient dialog -->
<!-- admit patient dialog -->
        <Dialog header="Admit Patient" v-model:visible="admitDialogVisible" :breakpoints="{ '960px': '75vw' }" :style="{ width: '30vw' }" :modal="true">
            <div class="card">
                <h5>Patient Details</h5>
                <div class="p-fluid formgrid grid">
                    <div class="field col-12 md:col-4">
                        <label for="firstname2">First Name</label>
                        <InputText v-model="patientToEdit.pat_firstname" disabled id="firstname2" type="text" />
                    </div>
                    <div class="field col-12 md:col-4">
                        <label for="middlename">Middle Name</label>
                        <InputText v-model="patientToEdit.pat_middlename" disabled id="middlename" type="text" />
                    </div>
                    <div class="field col-12 md:col-4">
                        <label for="lastname">Last Name</label>
                        <InputText v-model="patientToEdit.pat_lastname" disabled id="lastname" type="text" />
                    </div>
                    <div class="field col-12 md:col-4">
                        <label for="suffixname">Suffix name</label>
                        <InputText v-model="patientToEdit.pat_suffixname" disabled id="suffixname" type="text" />
                    </div>
                    <div class="field col-12 md:col-4">
                        <label for="birthdate">Birthdate</label>
                        <Calendar v-model="patientToEdit.pat_birth" disabled showIcon :showOnFocus="false" />
                    </div>
                    <div class="field col-12">
                        <label for="address">Address</label>
                        <Textarea v-model="patientToEdit.pat_address" disabled id="address" rows="4" />
                    </div>
                    <div class="field col-12 md:col-6">
                        <label for="birthdate">Admission Date and Time</label>
                        <Calendar id="calendar-12h" :required="true" :class="{'p-invalid': submitted && !patientAdmission.admission}"  v-model="patientAdmission.admission" showTime hourFormat="12" />
                    </div>
                    <div class="field col-12 md:col-6">
                        <label for="birthdate">Ward</label>
                        <Dropdown v-model="patientAdmission.ward" :required="true"  :options="ward"  placeholder="Select a Ward" class="w-full md:w-14rem" :class="{'p-invalid': submitted && !patientAdmission.ward}"/>
                    </div>
                    
                    
                </div>
            </div>

            <template #footer>
                <Button label="Cancel" @click="close" icon="pi pi-times" class="p-button-outlined" style="color:red" />
                <Button label="Admit" @click="admitPatient" icon="pi pi-check" class="p-button-outlined" />
            </template>
        </Dialog>

<!-- admit patient dialog -->

            
            <!-- <div class="card">
                <h5>Recently Discharged Patients</h5>
                <DataTable :value="products" :rows="5" :paginator="true" responsiveLayout="scroll">
                    <Column field="name" header="Name" :sortable="true" style="width: 35%"></Column>
                    <Column field="price" header="Price" :sortable="true" style="width: 35%">
                        <template #body="slotProps">
                            {{ formatCurrency(slotProps.data.price) }}
                        </template>
                    </Column>
                    <Column style="width: 15%">
                        <template #header> View </template>
                        <template #body>
                            <Button icon="pi pi-search" type="button" class="p-button-text"></Button>
                        </template>
                    </Column>
                </DataTable>
            </div> -->
        </div>
        <div class="col-12 xl:col-6">
            <div class="card">
                <h5>Active Admitted Patients</h5>
                <DataTable :value="currentlyAdmitted" :rows="5" :paginator="true" responsiveLayout="scroll">
                    <Column field="id" header="id" :sortable="true" style="width: 35%">
                        <template #body="slotProps">
                            {{ slotProps.data.pat_id }}
                        </template>
                    </Column>
                    <Column field="name" header="Name" :sortable="true" style="width: 35%">
                        <template #body="slotProps">
                            {{ slotProps.data.pat_firstname }}, {{ slotProps.data.pat_lastname }} {{ slotProps.data.pat_middlename }} {{ slotProps.data.pat_suffixname }}
                        </template>
                    </Column>
                    <Column field="price" header="Birthday" :sortable="true" style="width: 35%">
                        <template #body="slotProps">
                            {{ slotProps.data.pat_birth }}
                        </template>
                    </Column>
                    <Column field="price" header="Admitted Date" :sortable="true" style="width: 35%">
                        <template #body="slotProps">
                            {{ slotProps.data.admission }}
                        </template>
                    </Column>
                    <Column style="width: 15%">
                        <template #header> Discharge Date</template>
                        <template #body="slotProps">
                            <Button icon="pi pi-sign-out" @click="openDischargeDialog(slotProps.data)" type="button" class="p-button-text"></Button>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>

<!-- discharge patient -->
        <Dialog header="Discharge Patient" v-model:visible="dischargeDialogVisible" :breakpoints="{ '960px': '75vw' }" :style="{ width: '30vw' }" :modal="true">
            <div class="card">
                <h5>Patient Details</h5>
                <div class="p-fluid formgrid grid">
                    <div class="field col-12 md:col-4">
                        <label for="firstname2">First Name</label>
                        <InputText v-model="patientToEdit.pat_firstname" disabled id="firstname2" type="text" />
                    </div>
                    <div class="field col-12 md:col-4">
                        <label for="middlename">Middle Name</label>
                        <InputText v-model="patientToEdit.pat_middlename" disabled id="middlename" type="text" />
                    </div>
                    <div class="field col-12 md:col-4">
                        <label for="lastname">Last Name</label>
                        <InputText v-model="patientToEdit.pat_lastname" disabled id="lastname" type="text" />
                    </div>
                    <div class="field col-12 md:col-4">
                        <label for="suffixname">Suffix name</label>
                        <InputText v-model="patientToEdit.pat_suffixname" disabled id="suffixname" type="text" />
                    </div>
                    <div class="field col-12 md:col-4">
                        <label for="birthdate">Birthdate</label>
                        <Calendar v-model="patientToEdit.pat_birth" disabled showIcon :showOnFocus="false" />
                    </div>
                    <div class="field col-12">
                        <label for="address">Address</label>
                        <Textarea v-model="patientToEdit.pat_address" disabled id="address" rows="4" />
                    </div>
                    <div class="field col-12 md:col-6">
                        <label for="birthdate">Admission Date and Time</label>
                        <Calendar v-model="patientAdmission.admission" id="calendar-12h"  disabled showTime hourFormat="12" />
                    </div>
                    <div class="field col-12 md:col-6">
                        <label for="birthdate">Ward</label>
                        <Dropdown v-model="patientAdmission.ward" disabled placeholder="Select a Ward" class="w-full md:w-14rem" />
                    </div>
                    <div class="field col-12 md:col-6">
                        <label for="birthdate">Discharge Date and Time</label>
                        <Calendar id="calendar-12h" required :class="{'p-invalid': submittedDischarge && !patientAdmission.discharge}" v-model="patientAdmission.discharge" showTime hourFormat="12" />
                    </div>
                    {{ patientAdmission }}
                </div>
            </div>

            <template #footer>
                <Button label="Cancel" @click="close" icon="pi pi-times" class="p-button-outlined" style="color:red" />
                <Button label="Discharge" @click="dischargeUpdate" icon="pi pi-check" class="p-button-outlined" />
            </template>
        </Dialog>
<!-- discharge patient -->
    </div>
    
</template>

<style>
.button-container {
  display: flex;
  justify-content: space-between;
}
</style>