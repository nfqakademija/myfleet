<template>
	<div class="">
		<TheHeader />
		<main class="container">
<!--			<Table />-->
			<Vehicle />
		</main>
	</div>
</template>

<script>
	import Table from './components/Pages/List/Table';
	import TheHeader from './components/TheHeader';
	import BaseButton from './components/BaseComponents/BaseButton';
	import Vehicle from './components/Pages/Vehicle/Vehicle';

	export default {
		components: {
		    Table,
			TheHeader,
			BaseButton,
			Vehicle,
		},
	}
</script>

<style scoped lang="scss">

</style>