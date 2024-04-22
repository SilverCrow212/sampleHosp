<script setup>

</script>

<template>
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
    
</template>