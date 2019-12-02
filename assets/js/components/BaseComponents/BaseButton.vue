<template>
	<button
		:disabled="disabled"
		:class="[styles, {'Button--disabled': disabled}]"
		@click="$emit('click')"
	>
		<slot></slot>
		{{ name }}
	</button>
</template>

<script>
    export default {
        name: "BaseButton",
	    props: {
            name: String,
			styles: String,
		    disabled: Boolean,
	    }
    }
</script>

<style scoped>
	@import '../../../css/components/BaseComponents/Button.scss';
</style>