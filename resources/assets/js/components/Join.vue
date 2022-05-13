<template>
	<modal>
		<div slot='header'>
			<h4>JOIN</h4>
		</div>
		<div slot='body' class='scrollable' >
			<form class="form-horizontal scrollable" role="form" @submit.prevent="onSubmit" >
				<!-- we don't need view to show the data, everything handle by data -->
				<!-- <div v-for="(item, key) in errors">
					<div hidden="true" v-if='Array.isArray(item) && key != "message" ' class="form-group">
                        <label for="board_id" class="col-md-4 control-label">{{key}}</label>

                        <div class="col-md-6">
                            <input id="key" type="input" class="form-control" name="key" v-model='item[0]' required>
                        </div>
                    </div>
				</div> -->

                <div class="form-group">
                    <label for="board_id" class="col-md-4 control-label">Board Id</label>

                    <div class="col-md-6">
                        <input tabindex="-1" id="join-board-id" v-model="form.board_id" type="board_id" class="form-control" name="board_id"  required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-3 col-md-offset-4">
                        <loading v-if='isLoading' />
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12 col-xs-12">

                        <div :class='{"text-danger": hasError, "text-success": !hasError, "well":true, "text-center":true }'>
                            <strong> {{responseText}} </strong>
                        </div>
                    </div>
                </div>   
            </form>			
		</div>
		<div slot='footer'>
			<button class="btn btn-danger" @click="togglejoin">
                Close
            </button>
			<button class="btn btn-success" @click.prevent="onSubmit">
                Submit
            </button>
		</div>
	</modal>
	
</template>

<script >
	import modal from './Modal';
	import axios from 'axios';
	import loading from './Loading';

	export default {
		props: ['errors', 'form' ],
		components : {
			modal, loading
		},
		mounted(){
			let errors = this.errors;
			this.form.board_id ='';
			let form = this.form;
			console.log({ errors, form })
			document.getElementById('join-board-id').focus();
		},

		data(){
			return {
				responseText:'',
				isLoading : false,
				hasError : false,
			}
		},

		methods : {
			onSubmit(){
				let boardid = this.form.board_id;
				this.form.board_id = boardid.toUpperCase();
				let form = {
					'nik': this.form.nik,
					'ip': this.form.ip,
					'guid': this.errors['guid'][0],
					'modelname' : this.form.modelname,
					'board_id': this.form.board_id,
				}
				
				this.toggleLoading();

				console.log(form)
				axios.post('api/main', form )
				.then((response) => {
					console.log('success', response)
					this.hasError = false;
					let message = response.data.message
					this.responseText = message;
					this.form.board_id = '';
					this.toggleLoading();
				})
				.catch((error) => {
					let data = error.response.data;
					this.hasError = true;
                    console.log('ERROR', data)
                    let message = data.message;
                    this.responseText = message;
                    this.toggleLoading();
				});
			},

			toggleLoading(){
				this.isLoading = !this.isLoading;
			},

			togglejoin(){
				this.responseText = '';
				this.form.board_id='';
				this.$emit('toggleJoin')
			},
		}

	}
</script>