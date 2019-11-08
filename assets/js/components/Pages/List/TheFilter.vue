<template>
	<div>
		<BaseTitle classes="col-md-12" name="Paieškos Filtras" />
		<div class="Filter container">
			<div class="col-md-4">
				<BaseSelect />
			</div>
			<div class="col-md-4">
				<BaseInput placeholder="Valstybinis Numeris" />
			</div>
			<div class="col-md-12 Filter-action">
				<BaseButton name="Ieškoti" styles="Button Button--success" />
			</div>
		</div>
<!--		<Pagination />-->
	</div>
</template>

<script>
	import BaseSelect from "../../BaseComponents/BaseSelect";
	import BaseInput from "../../BaseComponents/BaseInput";
	import BaseButton from "../../BaseComponents/BaseButton";
	import BaseTitle from "../../BaseComponents/BaseTitle";
	import Pagination from "./Pagination";
	
    export default {
        name: "TheFilter",
	    components: {
            BaseButton,
            BaseSelect,
		    BaseInput,
		    BaseTitle,
		    Pagination,
	    }
    }
</script>

<style scoped lang="scss">
	@import '../../../../css/components/Pages/List/Filter.scss';
	@import '../../../../css/components/BaseComponents/Button.scss';
</style>