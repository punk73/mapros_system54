<template>
	<modal>
		<div slot='header'>
			<h4>Confirmation</h4>
		</div>
		<div slot='body'>
			<p> model "{{ config_modelname }}" is different from "{{server_modelname}}" </p>
			<p> You Need to choose which is correct </p>
		</div>
		<div slot='footer'>
			<button class="btn btn-success" @click='configOnClick' >
				config {{config_modelname }}
			</button>
			<button class="btn btn-info" @click='serverOnClick' >
				server {{ server_modelname }}
			</button>

		</div>
	</modal>
</template>

<script>
	import modal from './Modal.vue';
	export default {
		props :['config_modelname', 'server_modelname' ],
		components: {
			modal
		},
		methods: {
			configOnClick(){
				console.log('configOnClick');
				this.$emit('toggleConfirm');
				this.$emit('toggleModal', 'ERROR', 'you scan wrong parts!!');
			},
			serverOnClick(){
				console.log('serverOnClick');
				this.$emit('toggleConfirm');
				this.$emit('changeConfig', this.server_modelname );
			}
		}
	}
</script>