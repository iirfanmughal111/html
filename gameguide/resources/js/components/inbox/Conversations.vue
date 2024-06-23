<template>
  <div class="col-sm-8">
      <div class="feed-wrap right-inbox-userinfo">
          <message-composer
            :baseUrl="baseUrl"
            :contact="contact"
            @send="send"
          />

          <message-feed
            :baseUrl="baseUrl"
            :contact="contact"
            :messages="messages"
            :initConvLoading="initConvLoading"
	           :user="user"
          />
      </div>
  </div>
</template>
<script>
  import MessageComposer from './MessageComposer.vue';
  import MessageFeed from './MessageFeed.vue';
  import {mapMutations, mapActions } from 'vuex';

  export default {
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
      components: {
          MessageComposer,
          MessageFeed
      },
      methods: {
        ...mapActions({
              sendMessage: 'inbox/sendMessage',
          }),
          send(text) {
		          // console.log(this.contact)
                if (!this.contact) {
                    return;
                }

                this.$emit('new', text);
               /* axios.post('/conversation/send', {
                    contact_id: this.contact.id,
                    text: text
                }).then((response) => {
                    this.$emit('new', response.data);
                })*/
           
          },
      },
  };
</script>
