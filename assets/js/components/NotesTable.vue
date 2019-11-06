<template>
	<div class="Table">
		<table class="col-md-12 Table-content">
			<thead>
				<tr class="Table-header">
					<th v-for="(header, i) in activeTabTable.headers" :key="i" class="Table-column">{{ header }}</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(note, i) in notes" :key="i" class="Table-row">
					<td v-for="value in note" class="Table-data">{{ value }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</template>

<script>
    export default {
        name: 'NotesTable',
	    props: {
            type: Number,
		    notes: Array,
	    },
	    computed: {
            activeTabTable() {
                return this.tables.find(table => {
                    return table.type === this.type;
                });
            },
	    },
	    data() {
            return {
                tables: [
	                {
	                    type: 1,
		                headers: [
		                    'ID',
			                'Pavadinimas',
			                'Sukurta',
			                'Komentaras',
			                'Statusas',
		                ],
	                },
	                {
	                    type: 2,
		                headers: [
		                    'ID',
			                'Pavadinimas',
			                'Sukurta',
			                'Komentaras',
		                ],
	                },
	                {
	                    type: 3,
		                headers: [
		                    'ID',
			                'Pavadinimas',
			                'Sukurta',
			                'Suma',
			                'Komentaras',
		                ]
	                },
                ],
            }
	    },
    }
</script>

<style scoped>
	@import '../../css/components/Table.scss';
</style>