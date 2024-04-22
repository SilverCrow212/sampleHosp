<script setup>

</script>
<template>
   <div class="card">
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
    </div>
</template>