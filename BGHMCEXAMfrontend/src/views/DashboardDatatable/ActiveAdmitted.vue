<script setup>

</script>

<template>
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

</template>