<template>
	<div v-if='config.isGenerateFile' class="withBorder">
		
		<div class="form-group" >
		    <label  class="col-md-3 control-label">Generated File Name</label>
		    <div class="col-md-9">
		        <input  type="text" v-model='config.generatedFileName' class="form-control" required autofocus>
		    </div>
		</div>

		<div class="form-group">
            <div class=" col-md-6 col-md-offset-3 col-xs-12">
                <toggle-button 
                	v-model="config.isEnterActive" 
                	:sync='true' 
                	:color="'#2ab27b'" 
                	:labels="true"
                	@change='isEnterActiveOnChange'
            />

                <label for="isSendAjax"> Use Enter as Delimiter </label>
            </div>
        </div>

		<div class="form-group" v-if='!config.isEnterActive' >
		    <label  class="col-md-3 control-label">Delimiter</label>
		    <div class="col-md-9">
		        <input id="delimiter" ref='delimiter' type="text" v-model='config.delimiter' class="form-control" required autofocus>
		    </div>
		</div>		
	
	</div>
</template>

<script>
	import ToggleButton from 'vue-js-toggle-button/src/Button';
	
	export default {
		props : ['config'],

		components : {
			ToggleButton
		},

		methods : {
			isEnterActiveOnChange(){
				if(this.config.isEnterActive){
					this.config.delimiter = '\r\n';
				}else {
					// set time out to wait to element exists
					setTimeout(() => {
					    var delimiter = document.getElementById('delimiter');
						// console.log(delimiter)
						delimiter.focus();
				  	}, 20 )
				}
			}
		},
	}
</script>

<style>
	.withBorder {
		border: solid;
		border-color: #ddd;
		border-width: 2px;
		padding: 3px;
		margin: 2px;
	}
</style>