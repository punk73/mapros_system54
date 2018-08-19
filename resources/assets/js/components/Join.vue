<template>
	<div class="container"  >
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading"><h1>Join</h1></div>
					<div class="panel-body">
						<form class="form-horizontal" role="form" @submit.prevent="onSubmit" >
							<!-- <div v-for="(item, key) in errors">
								<div class="form-group">
	                                <label for="board_id" class="col-md-4 control-label">{{key}}</label>

	                                <div class="col-md-6">
	                                    <input id="key" type="input" class="form-control" name="key"  required>
	                                </div>
	                            </div>
							</div> -->


                            <div class="form-group">
                                <label for="board_id" class="col-md-4 control-label">Board Id</label>

                                <div class="col-md-6">
                                    <input id="board_id" v-model="form.board_id" type="board_id" class="form-control" name="board_id"  required>
                                </div>
                            </div>
                        </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script >
	export default {
		props: ['errors'],
		mounted(){
			console.log(this.props)
		}

	}
</script>