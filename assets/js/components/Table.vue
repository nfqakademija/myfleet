<template>
	<div class="Table">
		<div class="Table-title">
			<h3 class="Table-name">Transporto Priemonių Sąrašas</h3>
			<BaseButton name="Naujas" styles="Button Button--success" />
		</div>
		<table class="Table-content col-md-12">
			<thead>
				<tr class="Table-header">
					<th v-for="(header, i) in tableHeaders" :key="i" class="Table-column">{{ header }}</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(data, i) in tableData" :key="i" class="Table-row">
					<td class="Table-data">{{ data.id }}</td>
					<td class="Table-data">{{ data.countryNumber }}</td>
					<td class="Table-data">{{ data.vinCode }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</template>

<script>
	import BaseButton from "./BaseButton";
    export default {
        components: {
            BaseButton,
        },
        name: 'Table',
	    data() {
            return {
                tableHeaders: [
                    'ID',
	                'Valstybinis Numeris',
	                'VIN Kodas',
                ],
	            tableData: [
		            {
		                id: 1,
			            countryNumber: 'FOR515',
			            vinCode: '1ZVBP8AM5D5230076'
		            },
                    {
                        id: 1,
                        countryNumber: 'FOR515',
                        vinCode: '1ZVBP8AM5D5230076'
                    },
                    {
                        id: 1,
                        countryNumber: 'FOR515',
                        vinCode: '1ZVBP8AM5D5230076'
                    },
	            ]
            }
	    }
    }
</script>

<style scoped lang="scss">
	@import '../../css/components/Table.scss';
	@import '../../css/components/Button.scss';
</style>