<template>
	<div class="Vehicle">
<!--		<div class="Vehicle-pageTitle">-->
<!--			<Title name="Transporto Priemonės Informacija" classes="col-md-10 Title-page" />-->
<!--			<BaseButton name="Naujas Įrašas" styles="Button Button&#45;&#45;success" />-->
<!--		</div>-->
<!--		<div class="Vehicle-information">-->
<!--			<NotesTabs />-->
<!--		</div>-->
		<TheFilter />
	</div>
</template>

<script>
	import BaseButton from '../../BaseComponents/BaseButton';
	import NotesTable from "./NotesTable";
	import NotesTabs from "./NotesTabs";
	import Title from "../../Title";
	import Properties from "./Properties";
	import TheFilter from "../List/TheFilter";

    export default {
        name: 'Vehicle',
	    components: {
            BaseButton,
		    NotesTable,
		    NotesTabs,
		    Title,
		    TheFilter,
		    Properties,
	    },
	    data() {
            return {
            
            }
	    },
    }
</script>

<style scoped lang="scss">
	@import '../../../../css/components/Vehicle.scss';
	@import '../../../../css/components/Button.scss';
	@import "../../../../css/components/Title.scss";
</style>