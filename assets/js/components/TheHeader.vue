<template>
	<header class="Header">
		<div class="Header-logo">
			<img class="Header-image" src="../../../public/myfleet.png">
			<strong class="Header-name">My Fleet</strong>
		</div>
		<nav class="Header-menu">
			<ul class="Header-list">
				<li class="Header-listItem"><a class="Header-link" href="/">Prisijungti</a></li>
				<li class="Header-listItem"><a class="Header-link" href="/">Registruotis</a></li>
			</ul>
		</nav>
	</header>
</template>

<script>
    export default {
        name: 'TheHeader',
    }
</script>

<style scoped lang="scss">
	@import '../../css/components/Header.scss';
</style>