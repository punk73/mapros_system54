<template>
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="bg-info panel-heading custom-heading">
						<i class="fa fa-cogs"></i> Config
					</div>
					<div class="panel-body">
						<form class="form-horizontal">
							<div class="form-group">
                                <label for="nik" class="col-md-3 control-label">Current Model</label>
                                <div class="col-md-9">
                                    <input  type="text" v-model='config.model' class="form-control" required autofocus>
                                </div>
                            </div>

							<div class="form-group">
                                <label for="nik" class="col-md-3 control-label">IP Address</label>
                                <div class="col-md-9">
                                    <input  type="text" v-model='config.ip_address' class="form-control" required autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-3 col-md-offset-3">
                                    <a href="#/" class="btn btn-primary"><i class="fa fa-arrow-circle-left float-right"></i></a>
                                    <a href="#/" @click.prevent='save' class="btn btn-success"><i class="fa fa-save"></i></a>
                                </div>
                            </div>
						</form>
					</div>
				</div>
			</div>

		</div>
	</div>
</template>

<script>
	export default {
		data(){
			return {
				config : {
					model:'',
					ip_address:'',
				},
			}
		},

		mounted(){
			this.getConfig();
		},
		methods: {
			save(){
				localStorage.setItem('config', JSON.stringify(this.config) )
				this.$router.push('/');
			},

			getConfig(){
				let currentConfig = localStorage.getItem('config')
				if(currentConfig != null){
					currentConfig = JSON.parse(currentConfig);
					this.config = currentConfig; 
					
				}
			}
		}
	}
</script>