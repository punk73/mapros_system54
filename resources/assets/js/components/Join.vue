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
                        <input id="board_id" v-model="form.board_id" type="board_id" class="form-control" name="board_id"  required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12 col-xs-12">
                        <div class="well ">
                            {{responseText}}
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

	export default {
		props: ['errors', 'form' ],
		components : {
			modal
		},
		mounted(){
			let errors = this.errors;
			this.form.board_id ='';
			let form = this.form;
			console.log({ errors, form })
		},

		data(){
			return {
				responseText:''
			}
		},

		methods : {
			onSubmit(){
				let form = {
					'nik': this.form.nik,
					'ip': this.form.ip,
					'guid': this.errors['guid'][0],
					'modelname' : this.form.modelname,
					'board_id': this.form.board_id ,
				}

				console.log(form)
				axios.post('api/main', form )
				.then((response) => {
					console.log('success', response)
					let message = response.data.message
					this.responseText = message;
					this.form.board_id = '';
				})
				.catch((error) => {
					let data = error.response.data;
                    console.log('ERROR', data)
                    let message = data.message;
                    this.responseText = message;
				});
			},

			togglejoin(){
				this.responseText = '';
				this.form.board_id='';
				this.$emit('toggleJoin')
			},
		}

	}
</script>