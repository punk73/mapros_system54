<template>
	<div :class="{alert:true, 'alert-danger':isDanger, 'alert-success': !isDanger , 'alert-dismissible': true, 'show':true}" role="alert">
      {{ message + "." }} <a class="alert-link" @click.prevent='$emit("showDetailError")' >detail</a>
      <button class="close" @click='$emit("toggleAlert")' aria-label="close">
          <span aria-hidden="true">&times;</span>
      </button>
    </div>
</template>

<script>
	export default {
		props: ['showAlert', 'message', 'isDanger'],
		data(){
			return {
				'alert-success':false,
			}
		}
	}
</script>