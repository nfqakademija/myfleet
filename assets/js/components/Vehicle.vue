<template>
	<div class="Vehicle">
		<div class="Vehicle-pageTitle">
			<Title name="Transporto Priemonės Informacija" classes="col-md-10 Title-page" />
			<BaseButton name="Naujas Įrašas" styles="Button Button--success" />
		</div>
		<div class="Vehicle-information">
			<Title :name="properties.name" classes="col-md-10 Title-section" />
			<Properties :propertyValues="properties" />
			<Title name="Įrašai" classes="col-md-10 Title-section" />
			<NotesTabs />
		</div>
	</div>
</template>

<script>
	import BaseButton from './BaseButton';
	import NotesTable from "./NotesTable";
	import NotesTabs from "./NotesTabs";
	import Title from "./Title";
	import Properties from "./Properties";

    export default {
        name: 'Vehicle',
	    components: {
            BaseButton,
		    NotesTable,
		    NotesTabs,
		    Title,
		    Properties,
	    },
	    data() {
            return {
                properties: {
                    name: 'BMW',
	                model: 'M5',
	                bodyType: 'Sedanas',
	                year: '2009-10',
	                countryNumber: 'HHH555',
	                vinCode: '1ZVBP8AM5D5230076',
	                comment: 'Graži mašina',
                },
            }
	    },
    }
</script>

<style scoped lang="scss">
	@import '../../css/components/Vehicle.scss';
	@import '../../css/components/Button.scss';
	@import "../../css/components/Title.scss";
</style>