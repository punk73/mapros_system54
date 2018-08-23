<template>
  <div>
    <div class="container">
        <div class="row">

            <div v-if="showAlert" class="col-md-8 col-md-offset-2">
                <div class="alert alert-danger alert-dismissible show" role="alert">
                  {{ error + "." }} <a class="alert-link" @click.prevent='showDetailError' >detail</a>
                  <button class="close" data-dismiss="alert" aria-label="close">
                      <span aria-hidden="true">&times;</span>
                  </button>
                </div>
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
                            <div class="col-md-3 col-sm-3">
                                PROCESS: {{info.process}}
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
                                <label for="board_id" class="col-md-4 control-label">Board Id</label>

                                <div class="col-md-6">
                                    <input id="board_id" v-model="form.board_id" type="board_id" class="form-control" name="board_id"  required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3 col-md-offset-4">
                                    <loading v-if='isLoading' />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
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
  </div>
</template>

<script>
    const axios = require('axios');
    import modal from './Modal';
    import loading from './Loading';

    export default {
        data: () => {
            return {
                form : {
                    ip: '',
                    board_id: '',
                    nik: '',
                    modelname:'',
                },

                error: '',

                detailError: '',

                showAlert : false,

                info : {
                    line    : '',
                    proces  : '',
                    type    : '',
                },

                isLoading:false,
                showModal: false,

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
            modal, loading
        },

        methods : {
            onSubmit(){
                let data = this.form
                console.log(data)
                return;
                axios.post('api/main', data )
                    .then((response) => {
                        console.log(response)
                    })
                    .catch( (error) => {
                        let data = error.response.data;
                        console.log(data)
                        let message = data.message;
                        
                        if(message == 'view'){
                            this.returnJoin(data.errors);
                            return;
                        }

                        if(message == 'view-confirmation'){
                            this.returnViewConfirmation();
                            return;
                        }

                        this.handleError(message, data.errors );
                    })
            },

            handleError(message, detailError = '' ){
                this.error = message;
                this.detailError = detailError;
                this.showAlert = !this.showAlert;
            },

            returnViewConfirmation(){
                console.log('view-confirmation')
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

            toggleModal(){
                this.showModal = !this.showModal
                // this.isLoading = !this.isLoading;
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
              this.form.ip = config.ip_address;
            },

            getInfo(){
              let modal = this.modal;
              let self= this;
              axios.get('api/configs', {
                params:{
                  ip: this.form.ip
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
