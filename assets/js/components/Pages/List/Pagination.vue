<template>
	<div class="col-md-12 Pagination">
		<BaseButton
			 name="Buvęs"
			 styles="Button Button--success"
			 @click="previousPage()"
			 :disabled="currentPage === 1"
		/>
		<span class="Pagination-currentPage">{{ currentPage }}</span>
		<BaseButton
			name="Kitas"
			styles="Button Button--success"
			@click="nextPage()"
		/>
	</div>
</template>

<script>
	import BaseButton from "../../BaseComponents/BaseButton";
	
    export default {
        name: "Pagination",
	    components: {
            BaseButton,
	    },
	    data() {
            return {
                currentPage: 1,
            }
	    },
	    methods: {
            nextPage() {
                this.currentPage++;
            },
		    previousPage() {
                this.currentPage--;
		    },
	    },
    }
</script>

<style scoped lang="scss">
	@import '../../../../css/components/Pages/List/Pagination.scss';
	@import '../../../../css/components/BaseComponents/Button.scss';
</style>