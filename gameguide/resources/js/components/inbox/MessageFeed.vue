<template>
  <vue-custom-scrollbar class="scroll-area" ref="scroll" @ps-scroll-y="scrollHanle">
      <div class="col-12 mt-3">
          <h4 class="text-center chat-user-name" v-text="contact ? (contact.full_name || contact.first_name +' '+ contact.last_name) :'Select a contact'"/>
      </div>
			<template v-if="contact && initConvLoading == false">
					<template v-if="messages.length">
						  <template v-for="message in messages">
						     <!--  MESSAGE SENT LIST BOX -->
							  
							  <div class="chat-feed-wrap" v-if="message.sent_recieved == 'sent'">
							      <div class="thumb-wrap">
								  <img class="img-responsive profile-img" v-if="message.profile_photo" v-bind:src="message.profile_photo" />
								  
								  <div class="chat_contact_name_sent" v-else>
									<a href="javascript:void(0)"><span> {{message.first_name.substr(0, 1)}}</span></a>
								  </div>
								  </div>
								  <div class="feed-content">
								  <p v-text="message.text"/>
                  <MessageMedia :medias="message.message_media" v-if="message.message_media.length > 0"></MessageMedia>
								  <span class="time-right" v-text="message.sent_time"> </span> 
							  </div>
							  </div>
							  
							    <!--  MESSAGE RECIEVED LIST BOX -->
							  <div class="chat-feed-wrap recieved_msg" v-if="message.sent_recieved == 'received'">
                  <div class="thumb-wrap">
								    <img class="right profile-img" style="width:100%;"v-if="message.profile_photo" v-bind:src="message.profile_photo" />
  								  <div class="chat_contact_name_lsit right" v-else>
  									 <a href="javascript:void(0)"><span> {{message.first_name.substr(0, 1)}}</span></a>
  								  </div>
								  </div>
								   <div class="feed-content">
								    <h6>{{message.first_name}} {{message.last_name}}<span class="time-left" v-text="message.sent_time" > </span></h6>
									 <p v-text="message.text"/>
                    <MessageMedia :medias="message.message_media" v-if="message.message_media.length > 0"></MessageMedia>
							  </div>
							  </div>
							 
						  </template>
					</template>
					<template v-else>
              <div class="img-notfound">
                  <img class="img-responsive" :src="baseUrl+'images/chat-image.png'">
                  <h5>No message found </h5>
              </div>
						</template>
			</template>

           <infinite-loading  ref="infiniteLoading" direction="bottom" spinner="circles"  @infinite="infiniteHandler">
              <div slot="no-more"></div>
              <div slot="no-results"></div>
           </infinite-loading>
					
				
  </vue-custom-scrollbar>
</template>

<script>
 import vueCustomScrollbar from 'vue-custom-scrollbar'
 import "vue-custom-scrollbar/dist/vueScrollbar.css"  
  import InfiniteLoading from 'vue-infinite-loading'
  import MessageMedia from './MessageMedia';
  import { mapState, mapActions, mapMutations } from 'vuex';
  export default {
      components: {
          vueCustomScrollbar,
          MessageMedia
      },
      props: {
          baseUrl: {
              type: String,
              default: '',
          },
          contact: {
              type: Object,
              default: null,
          },
		      user: {
              type: Number,
              default: null,
          },
          messages: {
              type: Array,
              default: [],
          },
          initConvLoading:{
            type: Boolean,
            default:false
          }
      },
      data:function () {
      return {
          sortLeftPosition:true,
          page:1
        }
      },
      computed: {
        ...mapState({
            totalPages: state => state.inbox.totalPages,
        })
      },
      methods: {
        ...mapActions({
          getConversation : 'inbox/getConversation',
        }),
        scrollToBottom() {
            setTimeout(() => {
                this.$refs.scroll.$el.scrollTop = this.$refs.scroll.scrollHeight;
            }, 50);
        },
        infiniteHandler($state) {
          //console.log('infiniteHandler');
        setTimeout(() => {
          //console.log(this.page+' --- '+this.totalPages);
          if(this.page < this.totalPages)
            this.page = this.page + 1; 

          let params = {'page':this.page,'id':this.contact.id}    
          
          /*Execute if page grether than 1, to avoid duplicate*/
          if(this.page > 1 && this.messages.length > 0 && this.page <= this.totalPages){
            this.getConversation(params);
          }

          if(this.page < this.totalPages)
            $state.loaded();
          else
            $state.complete();
          }, 
        1000);
      }
      },
      watch: {
          contact(contact) {
            this.page = 1;
            if(this.$refs.infiniteLoading){
              this.$refs.infiniteLoading.stateChanger.reset(); 
            }
            this.scrollToBottom();
          },
          messages(messages) {
		        //console.log(messages);
            this.scrollToBottom();
          },
          totalPages(page){
            //console.log('pages='+page);
          }
      }
  };
</script>

<style scoped="">
  .scroll-area {
    height: 600px;
  }
</style>
