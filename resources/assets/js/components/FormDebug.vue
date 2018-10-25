<template>
	<div>
        <div class="form-group row" >
            <label class="col-md-3 col-xs-3 control-label">Content</label>
            <div class="col-md-4 col-xs-3 col-sm-4">
                <input  type="text" v-model='content.dummy' placeholder="dummy" class="form-control" required autofocus>
            </div>
            
            <div class="col-md-3 col-xs-3 col-sm-3">
                <input  type="text" v-model='content.serial' placeholder="serial set" class="form-control" required autofocus>
            </div>

            <div class="col-md-2 col-xs-3 col-sm-2">
                <a href="#" class="btn btn-warning btn-circle form-control" @click.prevent="DownloadBtnOnClick"><i class="fa fa-download"></i></a>
            </div>
        </div>
	</div>
</template>

<script>
	import ToggleButton from 'vue-js-toggle-button/src/Button';

	export default {
		components : {
			ToggleButton,
		},

		props:['config'],

		data(){
			return {
				content:{
					dummy: 'MAMST00100001',
					serial: 'PW9JKLMN',
				},


			}
		},

		methods : {
			DownloadBtnOnClick (){
				console.log(this.config, 'download')
                // this generated file is on main mounted events, so you need to open main view first
                // before it's useable'
                let dummy = this.content.dummy;
                var enter;
                
                if(this.config.isEnterActive){
                	enter = '\r\n';
                }else{
                	enter = this.config.delimiter;
                }

                let serial = this.content.serial;
                console.log({
                    dummy, enter, serial
                })
                this.$root.$emit('GeneratedFile', dummy, enter, serial  )
            }
		}
	}
</script>