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
                                <label for="ip_address" class="col-md-3 control-label">IP Address</label>
                                <div class="col-md-9">
                                    <v-select 
                                        v-model='config.ip' 
                                        label="ip_address" 
                                        :maxHeight='"200px"'
                                        :options="options"
                                        index="ip_address"
                                        @search="onSearch" >
                                        <template slot="option" slot-scope="option">
                                            {{ option.ip_address }} - {{ option.name }}
                                        </template>
                                    </v-select>
                                </div>
                            </div>

                            <!-- <div class="form-group">
                                <label  class="col-md-3 control-label">Jumlah Join</label>
                                <div class="col-md-9">
                                    <input-number
                                        :step="1"
                                        :min="1"
                                        :max="10"
                                        v-model='config.jumlahJoin'
                                        controls
                                        inline
                                    ></input-number>
                                </div>
                            </div> -->

                            <div class="form-group">
                                <div class=" col-md-6 col-md-offset-3 col-xs-12">
                                    <!-- <input type="checkbox" id="showSolder" v-model="config.showSolder"> -->
                                    <toggle-button id="showSolder" :sync='true' v-model="config.showSolder"  :color="'#2ab27b'" :labels="true"/>
                                    <label for="showSolder"> show solder options </label>
                                </div>
                            </div>

                            <div class="form-group" v-if="config.showSolder" >
                                <label for="uri" class="col-md-3 control-label">toggle solder mode code</label>
                                <div class="col-md-9">
                                    <input type="input" class=" form-control " placeholder="solder_active" v-model="config.toggleSolderMode" required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class=" col-md-6 col-md-offset-3 col-xs-12">
                                    <toggle-button id="showCritical" :sync='true' v-model="config.showCritical"  :color="'#2ab27b'" :labels="true"/>
                                    <label for="showCritical"> show critical textfield </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class=" col-md-6 col-md-offset-3 col-xs-12">
                                    <!-- <input type="checkbox" id="isGenerateFile" v-model="config.isGenerateFile"> -->
                                    <toggle-button id="isGenerateFile" v-model="config.isGenerateFile" :sync='true'  :color="'#2ab27b'" :labels="true"/>
                                    
                                    <label for="isGenerateFile"> Generate file on scan </label>
                                </div>
                            </div>

                            <generate-file-config :config='config' />

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

                            <div class="form-group">
                                <div class=" col-md-6 col-md-offset-3 col-xs-12">
                                    <toggle-button v-model="config.showNgoption" :sync='true' :color="'#960a0a'" :labels="true"/>
                                    <label for="showNgoption"> show NG button </label>
                                </div>
                            </div>

                            <div class="form-group" v-if="config.showNgoption" >
                                <label for="uri" class="col-md-3 control-label"> NG mode code</label>
                                <div class="col-md-9">
                                    <input type="input" class=" form-control " placeholder="ng_active" v-model="config.toggleNgMode" required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class=" col-md-6 col-md-offset-3 col-xs-12">
                                    <toggle-button v-model="config.checkEsd" :sync='true' :color="'#2ab27b'" :labels="true"/>
                                    <label for="checkEsd"> Check ESD </label>
                                </div>
                            </div>

                            <div class="form-group" v-if="config.checkEsd" >
                                <label for="uri" class="col-md-3 control-label"> URL data ESD </label>
                                <div class="col-md-9">
                                    <input type="input" placeholder="http://136.198.117.48/esd/api/esd" class="form-control" v-model="config.esdUri" required autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-9 col-md-offset-3">
                                    <a href="#/" class="btn btn-danger"><i class="fa fa-arrow-circle-left float-right"></i> Cancel</a>
                                    <a href="#/" @click.prevent='save' class="btn btn-success"><i class="fa fa-save"></i> Save </a>
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
    import GenerateFileConfig from './GenerateFileConfig';
    import Alert from './Alert';
    import InputNumber from '@chenfengyuan/vue-number-input';
    import vSelect from 'vue-select';
    import _ from 'lodash';
    import axios from 'axios';

	export default {

		data(){
			return {
				config : {
					model:'',
					ip:'',
                    
                    showSolder:false,
                    toggleSolderMode:'toggleSolderMode',

                    showCritical: false,
                    criticals:[{index:0}],

                    // jumlahJoin:1, //will deleted soon due to move to server

					showConfig: false,
					isGenerateFile : false,
					isSendAjax : false,
					isShowDeleteButton : false,
					isAutolinezero : false,
					generatedFileName : 'something.txt',
					uri : '',
                    isDebug : false,

                    showNgoption: false,
                    toggleNgMode: 'toggleNgMode',

                    checkEsd : true,
                    esdUri : 'http://136.198.117.48/esd/api/esd',
				},

                debug : {
                    enterActive : true,
                    content : {
                        dummy:'MAPNL01020001',
                        serial:'#NA',
                        enter : '\r\n',
                    },
                },

                options: [],

			}
		},

		components : {
			ToggleButton, GenerateFileConfig, Alert, InputNumber, vSelect
		},

		mounted(){
			this.getConfig();
            this.fetchIpData();
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

            onSearch(search, loading ){
                loading(true);
                this.search(loading, search, this );
            },

            search: _.debounce((loading, search, vm) => {
              const url = 'api/scanners/all';

              axios.get(url, {
                params : {
                    q : search
                }
              })
              .then(res => {
                // res.json().then(json => (vm.options = json.items));
                let response = res.data;
                let data = response.data;
                vm.options = data;
                loading(false);
              }).catch(res => {
                console.log(res)
              });

            }, 350),

            fetchIpData(){
                const url = 'api/scanners/all';
                axios.get(url)
                  .then(res => {
                    let response = res.data;
                    let data = response.data;
                    this.options = data;
                  }).catch(res => {
                    console.log(res)
                  });
            },

			getConfig(){
				let currentConfig = localStorage.getItem('config')
				if(currentConfig != null){
					currentConfig = JSON.parse(currentConfig);
					this.config = currentConfig; 
					
				}
			},
		}
	}
</script>