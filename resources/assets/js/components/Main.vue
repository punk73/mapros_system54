<template>
  <div>
    <div class="container">
        <div class="row">

            <div v-if="showAlert" class="col-md-8 col-md-offset-2">
                <alert  
                    @showDetailError='showDetailError'
                    @toggleAlert='toggleAlert'
                    :message='error'
                    :isDanger='hasError'
                ></alert>
            </div>

            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <!-- <div class="panel-heading custom-color">
                        <div class="row">
                           <div class="col-md-6 col-sm-6 col-xs-7">
                            LINE 1 : <strong> {{ info.line }} </strong>
                           </div>
                           <div class="col-md-6 col-sm-6 col-xs-5 text-right pull-right float-right">
                               TYPE : {{ info.type }}
                           </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-7">
                                PROCESS: <strong> {{info.process}} </strong>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-5 text-right pull-right float-right">
                               STEP ID : {{ info.lineprocess_id }}
                           </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                model: <strong> {{form.modelname}} </strong>
                            </div>
                        </div>
                    </div> -->
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" @submit.prevent='onSubmit' >
                            <div class="form-group text-center">
                                <h3><strong>PLEASE SCAN DATA</strong></h3>
                            </div>
                            <div class="form-group">
                                <label for="nik" class="col-md-4 control-label">NIK</label>
                                <div class="col-md-6">
                                    <div class="button-group">
                                        <input id="nik" type="search" maxlength="10" class="form-control" name="nik" v-model='form.nik' required autofocus @keyup.13.prevent='boardOnFocus'>
                                        <!-- <span id="searchclear" class="fa fa-window-close"></span> -->
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" hidden="true">
                                <label for="name" class="col-md-4 control-label">IP Address</label>

                                <div class="col-md-6">
                                    <input v-model='form.ip' id="ip_address" type="text" class="form-control" name="ip_address" required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">{{ label.id }}</label>

                                <div class="col-md-6">
                                    <input id="board_id" ref='board_id' v-model="form.board_id" type="board_id" class="form-control" name="board_id"  required>
                                </div>
                            </div>

                            <div v-if="config.showSolder" class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <!-- <input type="checkbox" id="checkbox" v-model="form.is_solder"> -->
                                    <toggle-button v-model="form.is_solder" :color="'#2ab27b'" :labels="true"/>
                                    <label for="checkbox"> SOLDER </label>
                                </div>
                            </div>  

                            <div class="form-group">
                                <div class="col-md-3 col-md-offset-4">
                                    <loading v-if='isLoading' />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12 col-xs-12">
                                    <div class="well" :style="styles" >
                                        <div class="custom-color">
                                            <div class="row">
                                               <div class="col-md-6 col-sm-6 col-xs-7">
                                                LINE : <strong> {{ info.line }} </strong>
                                               </div>
                                               <div class="col-md-6 col-sm-6 col-xs-5 text-right pull-right float-right">
                                                   TYPE : {{ info.type }}
                                               </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6 col-xs-7">
                                                    PROCESS: <strong> {{info.process}} </strong>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-5 text-right pull-right float-right">
                                                   STEP ID : {{ info.lineprocess_id }}
                                               </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    MODEL : <strong> {{form.modelname}} </strong>
                                                </div>
                                            </div>
                                            <hr class="black">
                                        </div>
                                        <!-- <div class="custom-color text-left">
                                            <div class="row">
                                               <div class="col-md-12 col-sm-12 col-xs-12">
                                                LINE 2 : <strong> {{ info.line }} </strong>
                                               </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    PROCESS: <strong> {{info.process}} </strong>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    model: <strong> {{form.modelname}} </strong>
                                                </div>
                                            </div>
                                            <hr class="black">
                                        </div> -->
                                        <div class="text-center">
                                            Information Status: <br>
                                            <!-- <div :class='{"text-danger": hasError, "text-success": !hasError }'> -->
                                                <strong> {{error}} </strong>
                                            <!-- </div> -->
                                            <!-- :class='{"text-danger": hasError, "text-success": !hasError }' -->
                                            <H2  ><strong>{{ (hasError) ? 'NG':'OK' }}</strong></H2>

                                            <a :style='styles' @click.prevent="showDetailError" >See Details >></a>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                            
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-success" >
                                        Submit <i class="fa fa-check float-right"></i>
                                    </button>

                                    <button v-if='config.isShowDeleteButton' @click.prevent='deleteOnClick'  type="button" class="btn btn-danger">
                                        Delete
                                    </button>

                                    <button @click.prevent='changesColor' class="btn btn-info">change color</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <modal 
        v-if="showModal" 
        @toggleModal='toggleModal'
       v-bind:message="modal.message"
       v-bind:header="modal.header"  
    ></modal>
    <confirm 
        v-if='showConfirm'
        @toggleModal='toggleModal'
        @toggleConfirm='toggleConfirm'
        @changeConfig='changeConfig'
        v-bind:config_modelname='form.modelname'
        v-bind:server_modelname='server.modelname'
    ></confirm>
    <join v-if='showJoin' 
        :form='form'
        :errors='errors'
        @toggleJoin='toggleJoin'
        @toggleModal='toggleModal'
    ></join>
  </div>
</template>

<script>
    const axios = require('axios');
    import modal from './Modal';
    import loading from './Loading';
    import confirm from './Confirm';
    import alert from './Alert';
    import join from './Join';
    import ToggleButton from 'vue-js-toggle-button/src/Button';
    export default {
        data: () => {
            return {
                form : {
                    ip: '',
                    board_id: '',
                    nik: '',
                    modelname:'',
                    is_solder:false,
                },

                server:{
                    modelname:''
                },

                error: '',
                hasError: false,

                responseText: '',

                detailError: '',

                showAlert : false,
                showJoin  : false,

                info : {
                    line    : '',
                    proces  : '',
                    type    : '',
                    lineprocess_id : '',
                },

                state : 'in',

                styles : {
                    // backgroundColor: '#ffffff',
                    // color : '#eeeeee'
                },

                isLoading:false,
                showModal: false,
                showConfirm:false,

                label : {
                    id : 'Board ID'
                },

                // it's basically will be override by getConfig method
                config : {
                    modelname: '',
                    ip       : '',
                    showSolder: true,
                    isGenerateFile : false,
                    isSendAjax : false,
                    isShowDeleteButton : false,
                    uri : '',
                },

                modal: {
                    header: 'Header',
                    message: 'message'
                },
            }
        },

        mounted(){
            // console.log('mounted')
            this.getConfig();
            this.getInfo();
        },

        components: {
            modal, loading, confirm, alert, join, ToggleButton,
        },

        methods : {
            onSubmit(){

                let data = this.form;
                // console.log(data);
                let self = this;
                this.toggleLoading();
                axios.post('api/main', data )
                    .then((response) => {
                        self.toggleLoading()
                        self.handleSucces(response)
                        console.log(response)
                    })
                    .catch( (error) => {
                        self.toggleLoading();

                        if(error == undefined ){
                            this.handleError('TOLONG RELOAD APLIKASI DENGAN F5!', {} )
                            return;
                        }

                        if(error.response == undefined ){
                            this.handleError('TOLONG RELOAD APLIKASI DENGAN F5!', {errors : error })
                            return;
                        }

                        if(error.response.data == undefined){
                            this.handleError('TOLONG RELOAD APLIKASI DENGAN F5!', { errors : error.response })
                            return;
                        }

                        let data = error.response.data;
                        console.log(data)
                        let message = data.message;
                        
                        if(message == 'view'){
                            this.returnJoin(data.errors);
                            return;
                        }

                        if(message == 'confirmation-view'){
                            this.returnViewConfirmation(data);
                            return;
                        }

                        this.handleError(message, data );
                    })
            },

            changesColor(color){
                let yellow = {
                    backgroundColor : '#e5ff12',
                    'border-color'    : '#888080',
                }

                let green = {
                    backgroundColor : '#11b90e',
                    color           : 'white',
                    'border-color'    : '##819289'
                }

                let red  = {
                    color : '#d2c6c6',
                    backgroundColor : '#8e0d0d',
                    'border-color' : '#888080'
                }

                /*if (this.state == 'yellow' ) {
                    this.styles = green;
                    this.state = 'green';
                }else if (this.state == 'green'){
                    this.styles = red;
                    this.state = 'red';
                }else {
                    this.state = 'yellow';
                    this.styles = yellow;
                }*/

                if(color == 'red'){
                    this.styles = red;
                }else if (color == 'yellow'){
                    this.styles = yellow;
                }else{
                    this.styles = green;
                }

            },

            download(data, filename, type) {
                var file = new Blob([data], {type: type});
                console.log('download');
                if (window.navigator.msSaveOrOpenBlob) // IE10+
                    window.navigator.msSaveOrOpenBlob(file, filename);
                else { // Others
                    var a = document.createElement("a"),
                            url = URL.createObjectURL(file);
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    setTimeout(function() {
                        document.body.removeChild(a);
                        window.URL.revokeObjectURL(url);  
                    }, 0); 
                }
            },

            boardOnFocus(){
                console.log(this.$event)
                return

                this.$event.target.nextElementSibling.focus()
            },

            handleError(message, detailError = '' ){
                this.error = message;
                this.detailError = detailError;
                this.hasError = true;
                this.changesColor('red');
                this.form.board_id='';
                // this.toggleAlert();
                // this.showAlert = true;
                // this.$refs.board_id.$el.focus();
            },

            handleSucces(response){
                // set error to default value to show alert-success in alert
                console.log('handleSucces', response )
                let message = response.data.message;
                this.hasError = false;
                this.error = message;

                if(this.config.isGenerateFile){
                    if (response.data.node.status != 'IN') return; //kalau dia bkn in, gausah download;
                    this.download(this.form.board_id, 'RUN_AVMT.txt' );
                }

                if( this.config.isSendAjax ){
                    if (response.data.node.status == 'IN') { 
                        //kalau dia bkn in, gausah download;
                        this.sendAjax()    
                    }
                }

                if(message.includes('IN')){
                    this.changesColor('yellow')
                }else {
                    this.changesColor('green')
                }
                // this.toggleAlert('Success', message );
                // this.showAlert = true;
                this.form.board_id = '';
                // set focus
            },

            deleteOnClick(){
                let data = this.form;
                let self = this;
                this.toggleLoading();

                axios.delete('api/main', data )
                    .then((response) => {
                        self.toggleLoading()
                        self.handleSucces(response)
                        console.log(response)
                    })
                    .catch( (error) => {
                        let data = error.response.data;
                        console.log(data)
                        let message = data.message;
                        self.toggleLoading()
                        
                        this.handleError(message, data );
                    })
            },

            returnViewConfirmation(error){
                this.server.modelname = error.errors['server-modelname'][0]
                console.log('view-confirmation', error.errors['server-modelname'][0] )
                this.showConfirm = !this.showConfirm;
            },

            showDetailError(){
                // show modal containing the error 
                console.log(this.detailError )
                let errors = this.detailError.errors
                this.toggleModal('Information', errors )
            },

            returnJoin(errors){
                this.errors = errors
                this.showJoin = true;
            },

            toggleModal(header = '', message = ''){
                this.modal.header = header;
                this.modal.message = message;
                this.showModal = !this.showModal
                // this.showConfirm = !this.showConfirm;
                // this.isLoading = !this.isLoading;
            },

            toggleConfirm(){
                this.showConfirm = !this.showConfirm
            },

            toggleLoading(){
                this.isLoading = !this.isLoading;
            },

            toggleAlert(){
                this.showAlert = !this.showAlert;
            },

            toggleJoin(){
                this.showJoin = !this.showJoin
            },

            getConfig(){
              let config = localStorage.getItem('config');
              if(config == null ){
                this.$router.push({
                  path: '/config'
                })
              }
              config = JSON.parse(config);
              this.config = config;
              console.log(config);
              this.form.modelname = config.model;
              this.form.ip = config.ip;
            },

            // triggered by child view
            changeConfig(serverModel){
                this.form.modelname = serverModel;
                let newConfig = {
                    model: serverModel,
                    ip : this.form.ip
                }

                localStorage.setItem('config', JSON.stringify(newConfig) );
                // changes localstorage
                this.onSubmit();
            },

            initLabel(){
                console.log(this.info, 'set label method')
                if( this.info.lineprocess != undefined ){
                    if(this.info.lineprocess.column_settings != undefined){
                        let column_settings = this.info.lineprocess.column_settings;
                        for (var i = 0; i < column_settings.length; i++) {
                            if( column_settings[i]['table_name'] == 'masters') {
                                this.label.id = 'DUMMY MASTER';
                                return;
                            }

                            if( (column_settings[i]['table_name'] == 'tickets') ) {
                                this.label.id = 'DUMMY TICKET';
                            }
                        }
                    }
                }
            },

            sendAjax(){
                console.log(this.config, 'sendAjax methods triggered')
                axios.get(this.config.uri, {
                    params : {
                        valscan : this.form.board_id
                    }
                }).then( (response) => {
                    console.log('Success', response )
                }).catch((error) => {
                    console.log('error', error )
                    
                })
            },

            getInfo(){
              let modal = this.modal;
              let self= this;
              let config = localStorage.getItem('config');
              let ipConfig = JSON.parse(config);
              let ip = this.form.ip || ipConfig.ip
              axios.get('api/configs', {
                params:{
                  ip: ip
                }
              }).then((response) => {
                console.log(response)
                self.info = response.data.data;
                self.initLabel();
              })
              .catch((error)=> {

                modal.header = 'ERROR';
                console.log(error.response)
                modal.message = error.response.data.message;
                self.showModal = !self.showModal;
              })
            },

            toggleHasError(hasError = ''){
                if(hasError == ''){
                    this.hasError = !this.hasError;
                }else{
                    this.hasError = hasError;
                }
            },
        }
    }

</script>

<style>
    .custom-color{
        background-image: none!important;
        /*background-color: yellow !important;*/
    }

    .black {
        border-color: #636B6F;
        border-width: 2px;
    }

    .txt-color {
        color: #ffffff;
    }

    .bg-color {
        background-color: #edf108;
    }
</style>
