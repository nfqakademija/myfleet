<template>
	<div class="Property col-md-6">
		<p class="Property-name col-md-12"><strong>Pavadinimas</strong> {{ propertyValues.name }}</p>
		<p class="Property-name col-md-12"><strong>Modelis</strong> {{ propertyValues.model }}</p>
		<p class="Property-name col-md-12"><strong>Kėbulo Tipas</strong> {{ propertyValues.bodyType }}</p>
		<p class="Property-name col-md-12"><strong>Pagaminimo Metai</strong> {{ propertyValues.year }}</p>
		<p class="Property-name col-md-12"><strong>Valstybinis Numeris</strong> {{ propertyValues.countryNumber }}</p>
		<p class="Property-name col-md-12"><strong>VIN Kodas</strong> {{ propertyValues.vinCode }}</p>
	</div>
</template>

<script>
    export default {
        name: "Properties",
	    props: {
            propertyValues: Object,
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
	@import '../../css/components/Property.scss';
</style>