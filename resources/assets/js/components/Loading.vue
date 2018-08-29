<template>
	<img v-bind:src="source" class="img-fluid" alt="Responsive image">	
</template>

<script>
	export default {
		data(){
			return	{
				source:'../storage/app/public/ajax-loader.gif'
			}
		}
	}
</script>