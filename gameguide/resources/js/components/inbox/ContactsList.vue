<template>
  <div class="col-md-4 col-sm-4">
      <div class="user-friends left-inbox-userinfo">
	  <div class="scroll">
          <h1> Inbox </h1>
		  
          <vue-custom-scrollbar class="scroll-area">
		  
		  <div v-for="(contact, key) in contacts">
		   <div  :key="key" @click.prevent="$emit('selected', contact)" :class="selectedContact && selectedContact.id === contact.id ? 'active' : ''" class="user-area">
					<div class="user-cont">
  					<!-- <span v-if="contact.unread > 0">{{ contact.unread }}</span> -->
  					<a href="#" v-if="contact.profile_photo"><img class="img-responsive img" v-bind:src="contact.profile_photo" :title="contact.full_name"></a>
  					
  					<div class="chat_contact_name" v-else>
  					<a href="javascript:void(0)"><span> {{contact.full_name.substr(0, 1)}}</span></a>
  				    </div>
  					
  					
  					<div class="user-des">
  					  <h5><a href="#" v-text="`${contact.full_name}`">  </a></h5>
  					</div>
					</div> 
          <div class="user-right-cont">
            <a href="#" class="msg-count" v-if="contact.unread > 0">{{ contact.unread }}</a>
          </div>
			</div>
		  </div>
		  
           
          </vue-custom-scrollbar>
      </div>
      </div>
  </div>
</template>
<script>
  import vueCustomScrollbar from 'vue-custom-scrollbar';
  export default {
    props: {
      contacts: {
          type: Array,
          default: [],
      },
      baseUrl: {
          type: String,
          default: '',
      },
      selectedContact: {
          type: Object,
          default: null,
      },
    },
    components: {
      vueCustomScrollbar
    },
  };
</script>
<style scoped = "">
  .active {
      background-color: #f7f6f4;
  }
</style>
