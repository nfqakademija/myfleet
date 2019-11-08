<template>
	<div class="Property col-md-12">
		<p class="Property-name col-md-12"><strong>Pavadinimas</strong> {{ properties.name }}</p>
		<p class="Property-name col-md-12"><strong>Modelis</strong> {{ properties.model }}</p>
		<p class="Property-name col-md-12"><strong>Kėbulo Tipas</strong> {{ properties.bodyType }}</p>
		<p class="Property-name col-md-12"><strong>Pagaminimo Metai</strong> {{ properties.year }}</p>
		<p class="Property-name col-md-12"><strong>Valstybinis Numeris</strong> {{ properties.countryNumber }}</p>
		<p class="Property-name col-md-12"><strong>VIN Kodas</strong> {{ properties.vinCode }}</p>
	</div>
</template>

<script>
    export default {
        name: "Properties",
	    props: {
            properties: Object,
	    },
	    data() {
            return {
                propertyNames: [
                    'Pavadinimas',
	                'Modelis',
	                'Kėbulo Tipas',
	                'Pagaminimo Metai',
	                'Valstybinis Numeris',
	                'VIN Kodas',
	                'Komentaras',
                ],
            }
	    }
    }
</script>

<style scoped>
	@import '../../../../css/components/Pages/Vehicle/Property.scss';
</style>