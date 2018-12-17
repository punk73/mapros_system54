<template>
	<div class="container">
		<input type="text" v-model='path' name="">
		<button class="btn btn-success" @click="playOk" >OK</button>
		<button class="btn btn-danger" @click="playNG" >NG</button>
		<button class="btn btn-warning" @click="playJoin" >Join</button>
	</div>
</template>

<script>
	export default {
		data(){
			return {
				path:''
			}
		},

		methods : {
			playSound (sound) {
		      if(sound) {
		        var audio = new Audio(sound);
		        audio.play();
		      }
		    },

		    playOk(){
		    	console.log('playOk')
		    	this.playSound('./storage/OK.mp3');
		    },

		    playNG(){
		    	this.playSound('./storage/NG.mp3');
		    },

		    playJoin(){
		    	this.playSound('./storage/join.mp3');
		    }
		}
	}
</script>