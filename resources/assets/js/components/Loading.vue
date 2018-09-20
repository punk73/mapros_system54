<template>
	<div>
		<img v-bind:src="source" class="img-fluid" alt="Responsive image"> LOADING ...
	</div>
</template>

<script>
	export default {
		data(){
			return	{
				source:'../storage/app/public/hourglass.svg'
				// source:'../storage/app/public/ajax-loader.gif'
			}
		}
	}
</script>