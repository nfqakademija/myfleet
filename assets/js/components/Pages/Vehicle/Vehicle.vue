<template>
	<div class="Vehicle">
		<div class="Vehicle-pageTitle">
			<BaseTitle name="Transporto Priemonės Informacija" classes="col-md-10 Title-page" />
			<BaseButton name="Naujas Įrašas" styles="Button Button--success" />
		</div>
		<div class="Vehicle-information">
			<NotesTabs />
		</div>
<!--		<TheFilter />-->
	</div>
</template>

<script>
	import BaseButton from '../../BaseComponents/BaseButton';
	import NotesTabs from "./NotesTabs";
	import BaseTitle from "../../BaseComponents/BaseTitle";
	import Properties from "./Properties";
	import TheFilter from "../List/TheFilter";

    export default {
        name: 'Vehicle',
	    components: {
            BaseButton,
		    NotesTabs,
		    BaseTitle,
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
	@import '../../../../css/components/Pages/Vehicle/Vehicle.scss';
	@import '../../../../css/components/BaseComponents/Button.scss';
	@import "../../../../css/components/BaseComponents/Title.scss";
</style>