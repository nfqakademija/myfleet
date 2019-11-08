<template>
	<div class="col-md-12 NotesCard">
		<div class="col-md-10 NotesCard-content">
			<div class="col-md-12 NotesCard-title">
				<h5>{{ card.name }}</h5> <span>, {{ card.createdAt }}</span>
			</div>
			
			<div class="col-md-12">
				<p>{{ card.comment }}</p>
			</div>
		</div>
		<div class="col-md-2 NotesCard-action">
			<BaseButton
			    v-if="cardType === 1"
				name="Atlikta"
				styles="Button Button--success"
			/>
		</div>
	</div>
</template>

<script>
	import BaseButton from "../../BaseComponents/BaseButton";
	
    export default {
        name: "NotesCard",
	    props: {
            card: Object,
		    cardType: Number,
	    },
	    components: {
            BaseButton,
	    }
    }
</script>

<style scoped>
	@import '../../../../css/components/Pages/Vehicle/NotesCard.scss';
	@import '../../../../css/components/BaseComponents/Button.scss';
</style>