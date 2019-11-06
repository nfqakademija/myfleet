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
	import Table from './components/Table';
	import TheHeader from './components/TheHeader';
	import BaseButton from './components/BaseButton';
	import Vehicle from './components/Vehicle';

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