<template>
	<div>
		<Title classes="col-md-12" name="Paieškos Filtras" />
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
	</div>
</template>

<script>
	import BaseSelect from "../../BaseComponents/BaseSelect";
	import BaseInput from "../../BaseComponents/BaseInput";
	import BaseButton from "../../BaseComponents/BaseButton";
	import Title from "../../Title";
	
    export default {
        name: "TheFilter",
	    components: {
            BaseButton,
            BaseSelect,
		    BaseInput,
		    Title,
	    }
    }
</script>

<style scoped lang="scss">
	@import '../../../../css/components/Filter.scss';
	@import '../../../../css/components/Button.scss';
</style>