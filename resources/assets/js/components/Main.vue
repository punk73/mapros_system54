<template>
  <div>
    <div class="container">
        <div class="row">

            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    
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

                            <div class="form-group">
                                <label class="col-md-4 control-label">{{ label.id }}</label>

                                <div class="col-md-6">
                                    <input  id="board_id" ref='board_id' v-model="form.board_id" type="board_id" @input='filterBoard' class="form-control" name="board_id"  required>
                                </div>
                            </div>

                            <div class="form-group" v-if='isJoin'>
                                <label class="col-md-offset-4 col-md-6"> Proses Join Active : <toggle-button v-model="isJoin" :color="'#2ab27b'" :labels="true" @change='isJoinOnChange' /></label>
                            </div>

                            <div class="form-group" v-if="config.isAutolinezero" >
                                <label class="col-md-4 control-label">{{ label.serialAutolinezero }}</label>

                                <div class="col-md-6">
                                    <input  id="serialAutolinezero" ref='serialAutolinezero' v-model="serialAutolinezero" type="serialAutolinezero" class="form-control" name="serialAutolinezero"  required>
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
                                    <button v-if='!config.isShowDeleteButton' type="submit" class="btn btn-success" >
                                        Submit <i class="fa fa-check float-right"></i>
                                    </button>

                                    <button v-if='config.isShowDeleteButton' @click.prevent='deleteOnClick'  type="submit" class="btn btn-danger">
                                        Delete <i class="fa fa-trash float-right"></i>
                                    </button>

                                    <button v-if="(config.isSendAjax || config.isGenerateFile) && responseData.message.includes('IN / OK') " @click.prevent='resendData' class="btn btn-warning">Resend Data <i class="fa fa-arrow-right"></i> </button>
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

                isJoin : false,

                oldForm : {
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

                isLoading:false,
                showModal: false,
                showConfirm:false,

                label : {
                    id : 'Board ID',
                    serialAutolinezero : 'Serial Set'
                },

                styles : {}, //dipakai di warna well

                // it's basically will be override by getConfig method
                config : {
                    modelname: '',
                    ip       : '',
                    showSolder: true,
                    isGenerateFile : false,
                    generatedFileName : 'something.txt',
                    isSendAjax : false,
                    isShowDeleteButton : false,
                    isAutolinezero : false,
                    uri : '',
                },

                serialAutolinezero:'', //default value of serial

                responseData: {
                    success: true,
                    message: '',
                },

                oldResponseData: {
                    success: true,
                    message: '',
                },

                downloadContent : null,

                modal: {
                    header: 'Header',
                    message: 'message'
                },
            }
        },

        watch:{
            clonedForm: function(newVal, oldVal){
              // console.log({newVal, oldVal})
              this.oldForm = oldVal;
            }, 

            clonedResponseData(newVal, oldVal ){
                this.oldResponseData = oldVal;
            }
        },

        computed:{
            clonedForm: function(){
               return JSON.parse(JSON.stringify( this.form ))
            },

            clonedResponseData(){
                return JSON.parse(JSON.stringify( this.responseData ))
            },
        },

        mounted(){
            // setting event on root 
            let self = this;
            this.$root.$once('GeneratedFile', ( dummy='MAMST', enter='\r\n', serial = '#NA') => {
                // your code goes here
                // let dummy = 'dummy_id_goes here';
                // let serial = 'serial goes here ';
                // let enter = '\n';
                let data = dummy + enter + serial ;
                let filename  = 'example.txt';
                self.download(data, filename );
            });
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
                        this.responseData = error.response.data;
                        console.log(data)
                        let message = data.message;
                        
                        if(message == 'view'){
                            let pesan = this.form.board_id + ' IN / OK';
                            this.hasError = false;
                            this.error = pesan;
                            this.detailError = pesan;
                            this.changesColor('yellow');
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

            filterBoard(evt){
                let board_id = this.form.board_id;
                if (board_id.includes('&')) {
                    this.form.board_id = '';
                    let el = document.querySelector( ':focus' );
                    if( el ) el.blur();
                    this.toggleModal('Information', 'HASIL SCAN MENGANDUNG "&" TOLONG ULANGI!')
                }
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
                console.log(data ,'download');
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
                let boardInput = document.getElementById('board_id');
                boardInput.focus()
                if(this.config.isGenerateFile && this.config.isAutolinezero ){
                    let serialAutolinezero = document.getElementById('serialAutolinezero');
                    serialAutolinezero.focus();
                }
                // console.log('board on focus triggered')
            },

            handleError(message, detailError = '' ){
                this.error = message;
                this.detailError = detailError;
                this.hasError = true;
                this.changesColor('red');
                this.form.board_id='';
            },

            handleSucces(response){
                // set error to default value to show alert-success in alert
                console.log('handleSucces', response )
                let message = response.data.message;
                this.responseData = response.data;
                this.hasError = false;
                this.error = message;
                this.detailError = message;

                if(this.config.isGenerateFile){
                    if (response.data.node.status == 'IN') { //kalau dia bkn in, gausah download;
                        this.generateFile();
                    }
                }

                if( this.config.isSendAjax ){
                    if (response.data.node.status == 'IN') { 
                        //kalau dia bkn in, gausah download;
                        // console.log(data, 'handleSucces sending ajax')
                        this.sendAjax(this.responseData)    
                    }
                }

                if(message.includes('IN')){
                    this.changesColor('yellow')
                }else {
                    this.changesColor('green')
                }
                // this.toggleAlert('Success', message );
                // this.showAlert = true;
                this.clearForm();
                this.boardOnFocus();
                // set focus
            },

            clearForm(){
                this.form.board_id = '';
                if(this.config.isGenerateFile) {
                    this.serialAutolinezero = '';
                }
            },

            generateFile(){
                if ( (typeof this.serialAutolinezero == 'undefined') || this.serialAutolinezero == '' ) {
                    this.serialAutolinezero = 'NA';
                }

                var enter = this.config.delimiter; //'';//'\r\n';
                this.downloadContent = this.form.board_id + enter + this.serialAutolinezero ;
                

                let filename = this.config.generatedFileName;
                this.download( this.downloadContent, filename );
            },

            deleteOnClick(){
                let data = this.form;
                console.log(data)
                // return;
                let self = this;
                this.toggleLoading();

                axios.delete('api/main', {data : data } )
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
                /*this.errors = errors
                this.showJoin = true;*/
                this.isJoin = true;
                this.form.guid = errors['guid'][0];

                this.clearForm();
                this.boardOnFocus();
            },


            isJoinOnChange(){
                if( this.isJoin == false ){
                    delete this.form.guid //delete guid property from form;
                }

                this.boardOnFocus();
            },

            toggleModal(header = '', message = ''){
                this.modal.header = header;
                this.modal.message = message;
                this.showModal = !this.showModal
                // this.showConfirm = !this.showConfirm;
                // this.isLoading = !this.isLoading;
                if(this.showModal === false ){
                    // set focus on board id;
                    this.boardOnFocus();
                }

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
                this.boardOnFocus();
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

            sendAjax(responseData){
                let scanner_id = responseData.node.scanner.id;
                let guid;
                let board_id = this.form.board_id;
                if(responseData.node.guid_master != null ){
                    guid = responseData.node.guid_master;
                }else if (responseData.node.guid_ticket != null ){
                    guid = responseData.node.guid_ticket;
                }else {
                    guid = 'noData';
                }

                // let value = board_id + '_' + guid + '_' + scanner_id ; //ini untuk nanti;
                let value = board_id;
                // console.log({responseData, value}, 'sendAjax methods triggered')
                
                axios.get(this.config.uri, {
                    params : {
                        valscan : value
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

            resendData(){
                if( (this.config.isSendAjax) && ( this.responseData != null) ){
                    this.sendAjax(this.responseData)
                }

                if(this.config.isGenerateFile){
                    if ( (typeof this.serialAutolinezero == 'undefined') || this.serialAutolinezero == '' ) {
                        this.serialAutolinezero = 'NA';
                    }

                    var enter = this.config.delimiter; //'';//'\r\n';
                    this.downloadContent = this.oldForm.board_id + enter + this.serialAutolinezero ;
                    

                    let filename = this.config.generatedFileName;
                    this.download( this.downloadContent, filename );
                }
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
