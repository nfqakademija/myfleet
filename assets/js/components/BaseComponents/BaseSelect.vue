<template>
	<label for="select">
		<select
			 id="select"
			 class="Select"
			 v-model="selectModel"
			 @change="setSelected"
		>
			<option selected value="">Kėbulo Tipas</option>
			<option>1</option>
			<option>2</option>
			<option>3</option>
		</select>
		<i class="fas fa-angle-down"></i>
	</label>
</template>

<script>
    export default {
        name: "BaseSelect",
        props: {
            styles: String,
        },
        methods: {
            setSelected() {
            }
        },
	    data() {
            return {
                selectModel: '',
            }
	    }
    }
</script>

<style scoped lang="scss">
	@import "../../../css/components/BaseComponents/Select.scss";
	
	label {
		width: 100%;
		
		select {
			position: relative;
			cursor: pointer;
		}
		
		i {
			position: absolute;
			top: 3px;
			right: 20px;
			font-size: 25px;
			color: teal;
			pointer-events: none;
			cursor: pointer;
		}
	}
</style>