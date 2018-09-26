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
                                    <input  type="text" v-model='config.ip' class="form-control" required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class=" col-md-6 col-md-offset-3 col-xs-12">
                                    <!-- <input type="checkbox" id="showSolder" v-model="config.showSolder"> -->
                                    <toggle-button id="isDebug" :sync='true' v-model="config.isDebug"  :color="'#2ab27b'" :labels="true"/>
                                    <label for="isDebug"> is Debug </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class=" col-md-6 col-md-offset-3 col-xs-12">
                                    <!-- <input type="checkbox" id="showSolder" v-model="config.showSolder"> -->
                                    <toggle-button id="showSolder" :sync='true' v-model="config.showSolder"  :color="'#2ab27b'" :labels="true"/>
                                    <label for="showSolder"> show solder options </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class=" col-md-6 col-md-offset-3 col-xs-12">
                                    <!-- <input type="checkbox" id="isGenerateFile" v-model="config.isGenerateFile"> -->
                                    <toggle-button id="isGenerateFile" v-model="config.isGenerateFile" :sync='true'  :color="'#2ab27b'" :labels="true"/>
                                    
                                    <label for="isGenerateFile"> Generate file on scan </label>
                                </div>
                            </div>

                            <div class="form-group" v-if='config.isGenerateFile'>
                                <label for="uri" class="col-md-3 control-label">Generated File Name</label>
                                <div class="col-md-9">
                                    <input  type="text" v-model='config.generatedFileName' class="form-control" required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class=" col-md-6 col-md-offset-3 col-xs-12">
                                    <!-- <input type="checkbox" id="isGenerateFile" v-model="config.isGenerateFile"> -->
                                    <toggle-button id="isAutolinezero" v-model="config.isAutolinezero" :sync='true'  :color="'#2ab27b'" :labels="true"/>
                                    
                                    <label for="isAutolinezero"> Autoline Zero </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class=" col-md-6 col-md-offset-3 col-xs-12">
                                    <!-- <input type="checkbox" id="isSendAjax" v-model="config.isSendAjax"> -->
                                    <toggle-button id="isSendAjax" v-model="config.isSendAjax" :sync='true' :color="'#2ab27b'" :labels="true"/>

                                    <label for="isSendAjax"> send data to avn test / avmt </label>
                                </div>
                            </div>

                            <div class="form-group" v-if='config.isSendAjax'>
                                <label for="uri" class="col-md-3 control-label">URI</label>
                                <div class="col-md-9">
                                    <input  type="text" v-model='config.uri' class="form-control" required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class=" col-md-6 col-md-offset-3 col-xs-12">
                                    <!-- <input type="checkbox" v-model="config.isShowDeleteButton"> -->
                                    <toggle-button v-model="config.isShowDeleteButton" :sync='true' :color="'#2ab27b'" :labels="true"/>
                                    <label for="isShowDeleteButton"> show delete button </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-9 col-md-offset-3">
                                    <a href="#/" class="btn btn-danger"><i class="fa fa-arrow-circle-left float-right"></i> Cancel</a>
                                    <a href="#/" @click.prevent='save' class="btn btn-success"><i class="fa fa-save"></i> Save </a>

                                    <a v-if='config.isDebug' href="#/" @click.prevent='download' class="btn btn-warning"><i class="fa fa-download"></i> Test Download </a>
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
	import ToggleButton from 'vue-js-toggle-button/src/Button';

	export default {
		data(){
			return {
				config : {
					model:'',
					ip:'',
					showConfig: false,
					isGenerateFile : false,
					isSendAjax : false,
					isShowDeleteButton : false,
					isAutolinezero : false,
					generatedFileName : 'something.txt',
					uri : '',
                    isDebug : false,
				},
			}
		},

		components : {
			ToggleButton, 
		},

		mounted(){
			this.getConfig();
		},

		methods: {
			save(){
				if (this.config.isSendAjax) {
					if ( typeof this.config.uri == undefined ) {
						alert('you need to fill URI');
						return;
					}
				}

				
				localStorage.setItem('config', JSON.stringify(this.config) )
				this.$router.push('/');
			},

			getConfig(){
				let currentConfig = localStorage.getItem('config')
				if(currentConfig != null){
					currentConfig = JSON.parse(currentConfig);
					this.config = currentConfig; 
					
				}
			},

            download (){
                // this generated file is on main mounted events, so you need to open main view first
                // before it's useable'
                this.$root.$emit('GeneratedFile')
            }
		}
	}
</script>