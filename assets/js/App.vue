<template>
	<div class="container">
		<div class="Main mt-4">
			<div class="col-md-5 Main-title">
				<h3 class="Main-headline">
					Pradėkite perversmą savo įmonėje
				</h3>
				<p class="Main-subHeadline my-3">Valdyti transporto priemones dar niekada nebuvo taip paprasta</p>
				<BaseButton class="Button Button--primary" name="Prisijungti" />
			</div>
			<div class="col-md-7">
				<img class="Main-image" src="../../public/Main-image.svg">
			</div>
		</div>
		<div class="Services mt-5">
			<div class="col-md-12">
				<h4 class="Services-title">Ką mes siūlome</h4>
			</div>
			<div class="Services-content mt-4">
				<div class="col-md-3 mb-3 mx-2 Block Block-content">
					<img class="Service-image" src="../../public/list-service.svg">
					<p class="Service-description mt-3">Transporto priemonių parko valdymą realiu laiku su išsamia informacija apie kiekvieną transporto priemonę</p>
				</div>
				<div class="col-md-3 mb-3 mx-2 Block Block-content">
					<img class="Service-image" src="../../public/search-service.svg">
					<p class="Service-description mt-3">Paieškos variklį, padėsiantį surasti ir filtruoti norimas transporto priemones</p>
				</div>
				<div class="col-md-3 mb-3 mx-2 Block Block-content">
					<img class="Service-image" src="../../public/map-service.svg">
					<p class="Service-description mt-3">Transporto priemonės buvimo vietą realiu laiku</p>
				</div>
				<div class="col-md-3 mb-3 mx-2 Block Block-content">
					<img class="Service-image" src="../../public/task-service.svg">
					<p class="Service-description mt-3">Transporto priemonės išsamios informacijos valdymą, užduočių, įvykių ar išlaidų pildymą, galimybę redaguoti esamą informaciją</p>
				</div>
				<div class="col-md-3 mb-3 mx-2 Block Block-content">
					<img class="Service-image" src="../../public/data-service.svg">
					<p class="Service-description mt-3">Galimybę stebėti kasdien atsinaujinančią transporto priemonės informaciją iš išorinių šaltinių</p>
				</div>
				<div class="col-md-3 mb-3 mx-2 Block Block-content">
					<img class="Service-image" src="../../public/support-service.svg">
					<p class="Service-description mt-3">Pilną sistemos palaikymą ir sisteminių problemų sprendimą 24 valandas per parą</p>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import BaseButton from "./components/BaseComponents/BaseButton";
	export default {
		components: {
		    BaseButton,
		}
	}
</script>

<style scoped lang="scss">
	@import '../css/components/Pages/Home/Main.scss';
	@import '../css/components/Pages/Home/Services.scss';
	@import '../css/components/BaseComponents/Block.scss';
</style>