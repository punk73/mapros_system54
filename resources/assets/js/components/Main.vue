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
                    <div class="panel-heading custom-color">
                        <div class="row">
                           <div class="col-md-4 col-sm-4">
                            LINE : {{ info.line }}
                           </div>
                           <div class="col-md-4 col-md-offset-4">
                               TYPE : {{ info.type }}
                           </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                PROCESS: {{info.process}}
                            </div>
                            <div class="col-md-4 col-md-offset-4">
                               STEP ID : {{ info.lineprocess_id }}
                           </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" @submit.prevent="onSubmit" >
                            <div class="form-group">
                                <label for="nik" class="col-md-4 control-label">NIK</label>

                                <div class="col-md-6">
                                    <input id="nik" type="text" maxlength="6" class="form-control" name="nik" v-model='form.nik' required autofocus>
                                </div>
                            </div>

                            <div class="form-group" hidden="true">
                                <label for="name" class="col-md-4 control-label">IP Address</label>

                                <div class="col-md-6">
                                    <input v-model='form.ip' id="ip_address" type="text" class="form-control" name="ip_address" required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Board Id</label>

                                <div class="col-md-6">
                                    <input id="board_id" ref='board_id' v-model="form.board_id" type="board_id" class="form-control" name="board_id"  required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <input type="checkbox" id="checkbox" v-model="form.isSolder">
                                    <label for="checkbox"> is solder </label>
                                </div>
                            </div>  

                            <div class="form-group">
                                <div class="col-md-3 col-md-offset-4">
                                    <loading v-if='isLoading' />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary" @click.prevent='onSubmit' >
                                        Submit
                                    </button>

                                    <button @click='toggleModal'  type="button" class="btn btn-primary">
                                        show modal
                                    </button>

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
  </div>
</template>

<script>
    const axios = require('axios');
    import modal from './Modal';
    import loading from './Loading';
    import confirm from './Confirm';
    import alert from './Alert';

    export default {
        data: () => {
            return {
                form : {
                    ip: '',
                    board_id: '',
                    nik: '',
                    modelname:'',
                    isSolder:false,
                },

                server:{
                    modelname:''
                },

                error: '',
                hasError: false,

                detailError: '',

                showAlert : false,

                info : {
                    line    : '',
                    proces  : '',
                    type    : '',
                    lineprocess_id : '',
                },

                isLoading:false,
                showModal: false,
                showConfirm:false,

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
            modal, loading, confirm, alert
        },

        methods : {
            onSubmit(){
                let data = this.form;
                console.log(data);
                let self = this;
                this.toggleLoading();
                axios.post('api/main', data )
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

            handleError(message, detailError = '' ){
                this.error = message;
                this.detailError = detailError;
                this.hasError = true;

                this.toggleAlert();
                // this.$refs.board_id.$el.focus();
            },

            handleSucces(response){
                // set error to default value to show alert-success in alert
                // console.log('handleSucces', response )
                let message = response.data.message;
                this.hasError = false;
                this.error = message;
                this.toggleAlert();
            },

            returnViewConfirmation(error){
                this.server.modelname = error.errors['server-modelname'][0]
                console.log('view-confirmation', error.errors['server-modelname'][0] )
                this.showConfirm = !this.showConfirm;
            },

            showDetailError(){
                // show modal containing the error 
                console.log(this.detailError )
            },

            returnJoin(errors){
                this.$router.push({
                    path: '/join',
                    params: errors
                });
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

            getConfig(){
              let config = localStorage.getItem('config');
              if(config == null ){
                this.$router.push({
                  path: '/config'
                })
              }
              console.log(config);
              config = JSON.parse(config);
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
              })
              .catch((error)=> {

                modal.header = 'ERROR';
                console.log(error.response)
                modal.message = error.response.data.message;
                self.showModal = !self.showModal;
              })
            }
        }
    }

</script>

<style>
    .custom-color{
        background-image: none!important;
        background-color: white !important;
    }
</style>
