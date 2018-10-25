<template>
	<!-- Button trigger modal -->
<!-- Modal -->
<div class="modal-mask">
	<div  class="modal show" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <!-- <h5 class="modal-title" id="exampleModalLongTitle">{{header}}</h5> -->
	        <slot name="header">
	          	<h3 class="modal-title">{{header}}</h3>
		        <button @click="$emit('toggleModal')" type="button" class="close btn btn-danger"  aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
	        </slot>
	      </div>
          <div class="modal-body scrollable">
          	<div class="container-fluid">
          		<div class="row">
          			<div class="col-md-12 col-xs-12 col-sm-12">
			            <slot name="body">
			              <vue-json-pretty
						      :data="message"
						  >
						  </vue-json-pretty>
			            </slot>
          			</div>
          		</div>
          	</div>
          </div>
	      <div class="modal-footer">
	        <!-- <button type="button" class="btn btn-secondary" @click="$emit('toggleModal')" data-dismiss="modal">Close</button> -->
	        <slot name="footer">
              <button class="btn btn-success" @click="$emit('toggleModal')">
                Close
              </button>
            </slot>
	        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
	      </div>
	    </div>
	  </div>
	</div>
</div>
</template>

<script>
	import VueJsonPretty from 'vue-json-pretty'
	export default {
		props: {
			header: {
				type: String,
			},
			message: {
				
			}
		},

		components : {
			'vue-json-pretty' : VueJsonPretty
		},

		data(){
			return {

			}
		},
	}
</script>

<style>
	.modal-mask {
	  position: fixed;
	  z-index: 9998;
	  top: 0;
	  left: 0;
	  width: 100%;
	  height: 100%;
	  background-color: rgba(0, 0, 0, .5);
	  display: table;
	  transition: opacity .3s ease;
	  /*padding-right: 0 !important;*/
	}

	.modal-dialog {
		overflow-y: initial !important
	}

	.modal-body {
		max-height: 60vh;
    	overflow-y: auto;
	}

	/*.modal-wrapper {
	  display: table-cell;
	  vertical-align: middle;
	}

	.modal-container {
	  width: 300px;
	  margin: 0px auto;
	  padding: 20px 30px;
	  background-color: #fff;
	  border-radius: 2px;
	  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
	  transition: all .3s ease;
	  font-family: Helvetica, Arial, sans-serif;
	}

	.modal-header h3 {
	  margin-top: 0;
	  color: #42b983;
	}

	.modal-body {
	  margin: 20px 0;
	}*/

	/*.modal-default-button {
	  float: right;
	}*/

	/*
	 * The following styles are auto-applied to elements with
	 * transition="modal" when their visibility is toggled
	 * by Vue.js.
	 *
	 * You can easily play with the modal transition by editing
	 * these styles.
	 */

	/*.modal-enter {
	  opacity: 0;
	}

	.modal-leave-active {
	  opacity: 0;
	}*/
</style>