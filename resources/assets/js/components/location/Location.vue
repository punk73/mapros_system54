<template>
	<div class="container">
		<div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                	<div class="panel-body">
                		<div class="form-group">
	          				<label> Ref Number </label>
	          				<v-select
	          					v-model='model.ref_number'
	          					placeholder="Ketik untuk mencari ref number"
                                label="ref_no" 
                                :maxHeight='"200px"'
                                :options="locations"
                                :ref="'ref_number'"
                                required
                                @search="onSearch" 
	          				>
	          					<template slot="option" slot-scope="option">
                                    {{ option.modelname }} - {{ option.pwbname }} - {{ option.ref_no }}
                                </template>
	          				</v-select>      	
                		</div>
                		<div class="form-group">
                			<label for='symptoms'>Symptoms</label>
	                		<v-select
		                        placeholder='ketik untuk kode symptom / kategori symptom'
		                        multiple 
		                        v-model='model.symptoms'
		                        :maxHeight='"200px"' 
		                        label="category" 
		                        :options="symptoms"
		                        :ref="'symptoms'"
		                        required
		                        @search="onSearch" >
		                        <template slot="option" slot-scope="option">
		                            {{ option.code }} - {{ option.category }}
		                        </template>
		                    </v-select>
                		</div>
                		<div class="form-group">
                			<button class="btn btn-success" @click="addOnClick">ADD</button>
                		</div>

                		 <b-table striped hover :fields="fields" :items="stores">
                		 	<template slot="button" slot-scope="data">
						      <button class="btn btn-danger">Delete</button>
						    </template>
                		 </b-table>
                	</div>
                </div>
            </div>
        </div>
	</div>

</template>

<script>
	import vSelect from 'vue-select';
	import axios from 'axios';

	export default {
		data(){
			return {
				locations:[],
				symptoms :[],
				fields : ['ref_number','symptoms', 'button'],
				model: {
					ref_number: null,
					symptoms: [],
				},
				stores:[],
			}
		},

		computed : {
			form(){
				let result = {}
				if (this.model.ref_number !== null ) {
					result.ref_number = this.model.ref_number.id
				}

				if (this.model.symptoms.length > 0) {
					result.symptoms = [];
					this.model.symptoms.forEach(function(symptom){
						result.symptoms.push( symptom.code )
					})
				}

				return result;
			},

			row(){
				let ref_number = null;
				let symptoms = null;

				if (this.model.ref_number !== null ) {
					ref_number = this.model.ref_number.ref_no
				}

				if (this.model.symptoms.length > 0) {
					let arraySymptomsName = this.model.symptoms.map(function(symptom){
						return symptom['category']
					});

					symptoms = arraySymptomsName.toString();
				}

				return {
					ref_number : ref_number,
					symptoms : symptoms
				}
			}
		},

		components: {
			vSelect, 
		},

		mounted(){
			this.fetchLocations();
			this.fetchSymptomCode();
		},

		methods: {
			onSearch(){

			},

			addOnClick(){
				console.log('model', this.model)
				console.log('form', this.form)
				if (this.verifyForm() == false) {
					// stop the code here
					return;
				}
				this.stores.push( this.row )
				this.clear()

			},

			verifyForm(){
				var result = true;
				if (this.row.ref_number == null) {
					// console.log(this.$refs , "aku ref")
					let el = this.$refs['ref_number'];
					if(el){
						el.select();
					}
					result = false;
				}

				if (this.row.symptoms == null) {
					let el = this.$refs['symptoms'];
					if (el) {
						el.select()
					}
					result = false;
				}

				return result;
			},

			clear(){
				this.model =  {
					ref_number: null,
					symptoms: [],
				}
			},
			
			fetchLocations(){
				const url = 'api/locations';
				axios.get(url)
				.then(res => {
					console.log(res)
					let response = res.data; 
					let data = response.data;
					this.locations = data;
				}).catch(res => {
					console.log(res)
				})
			},

			fetchSymptomCode(){
                //fetch data for selectbox;
              const url = 'api/symptoms/all';

              axios.get(url)
              .then(res => {
                // res.json().then(json => (vm.options = json.items));
                let response = res.data;
                let data = response.data;
                this.symptoms = data;
              }).catch(res => {
                console.log(res , 'error fetch data!')
              });
            },
		}
	}
</script>