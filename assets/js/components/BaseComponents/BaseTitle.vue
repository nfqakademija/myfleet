<template>
	<div :class="classes">
		<h4>{{ name }}</h4>
	</div>
</template>

<script>
    export default {
        name: "BaseTitle",
	    props: {
            name: String,
		    classes: String,
	    }
    }
</script>

<style scoped>

</style>