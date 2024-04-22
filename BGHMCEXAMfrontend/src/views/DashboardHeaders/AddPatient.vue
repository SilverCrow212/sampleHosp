<script setup>

</script>
<template>
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
</template>