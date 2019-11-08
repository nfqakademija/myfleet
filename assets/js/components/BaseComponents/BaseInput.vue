<template>
	<input :placeholder="placeholder" class="Input" :type="type">
</template>

<script>
    export default {
        name: 'BaseInput',
        props: {
            placeholder: String,
            styles: String,
            type: String,
        },
    }
</script>

<style scoped>
	@import '../../../css/components/Input.scss';
</style>