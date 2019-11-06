<template>
	<button :class="styles">
		{{ name }}
	</button>
</template>

<script>
    export default {
        name: "BaseButton",
	    props: {
            name: String,
			styles: String,
	    }
    }
</script>

<style scoped>

</style>