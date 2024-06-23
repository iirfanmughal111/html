<template>
	<div v-if="medias.length >0">
		<VueSlickCarousel ref="slick" :arrows="true" :dots="false">
			<template v-for="media in medias">
				<div class="media_data chat-image" v-if="media.upload_type == 'image'">
					<img :src="media.media_url" :alt="media.media"> 
				</div>
				<div class="media_data chat-video" v-if="media.upload_type == 'video'">
					<video width="660" height="375" controls controlsList="nodownload" class="video-responsive video">
						<source :src="media.media_url" type="video/mp4">
						<source :src="media.media_url" type="video/ogg">
						<source :src="media.media_url" type="video/webm">
						<source :src="media.media_url" type="video/3gp" >
						Your browser does not support the video tag.
					</video>
				</div>
				<div class="media_data chat-audio" v-if="media.upload_type == 'audio'">
					<audio width="660" height="375" controls controlsList="nodownload" class="video-responsive video">
						<source :src="media.media_url" type="audio/mpeg">
						<source :src="media.media_url" type="audio/ogg">
						<source :src="media.media_url" type="audio/webm">
						<source :src="media.media_url" type="audio/mp3">
						Your browser does not support the audio tag.
					</audio> 
				</div>
			</template>
		</VueSlickCarousel>
	</div>
</template>

<script>
	import VueSlickCarousel from 'vue-slick-carousel';
	import 'vue-slick-carousel/dist/vue-slick-carousel.css';
	import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css';
	export default {
		components: { 
			VueSlickCarousel 
		},
	    props: {
			medias: {
			  type: Array,
			  default: [],
			},
	    },
	    methods:{
	    	reInit() {
	    		if (this.$refs.slick) {
	                // Helpful if you have to deal with v-for to update dynamic lists
	                //this.$refs.slick.reSlick();
	            }
            }
	    },
	    watch:{
	    	medias: function () {
	    		this.$nextTick(() => {
	              this.reInit();
	            });
	    	}
	    }
  	};
</script>