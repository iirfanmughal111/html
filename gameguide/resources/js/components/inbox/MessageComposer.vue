<template>
  <div class="panel-wrap chat-box">

        <div class="chat-box-wrap"> 
            <textarea
              class="form-control"
              v-model="message"
              spellcheck="false"
              @keydown.enter.default="send"
              placeholder="Type a message...">
            </textarea>
            <a href="javascript:void(0);" class="open_media" @click="toggleDropzone"> <i class="fa fa-camera" aria-hidden="true"></i> </a>
            <div class="error" v-show="isemptyContent == true">Please enter message or upload any media to send message</div>
        </div>
        <!--<button-spinner
            class="send"
            :is-loading="isLoading" 
            :disabled="isLoading"
            @click="send">
            <span>Send</span>
        </button-spinner>-->
          <a class="send-btn btn" href="javascript:void(0)"
              @click.prevent.default="send"><i class="fa fa-spinner fa-spin" style="font-size:16px" v-show="isLoading == true"></i> Send
          </a>
		  
            <vue-dropzone ref="myVueDropzone" id="dropzone" :options="dropzoneOptions" v-on:vdropzone-sending="sendingEvent"  @vdropzone-complete="afterComplete" @vdropzone-success-multiple="successUpload" @vdropzone-error="errorUpload" @vdropzone-files-added="filesAdded" v-show="isDropZonevisible == true"></vue-dropzone>		  

  </div>  
</template>

<script>
    import { mapState } from 'vuex';
    import vue2Dropzone from 'vue2-dropzone';
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'
    export default {
      components: {
        vueDropzone: vue2Dropzone
      },
		  data(){
          return {
              isDropZonevisible:false,
              message: '',
              isemptyContent : false,
              isLoading:false,
              isFileAddedDropzone:0, /*Flag that set,when there is atleast 1 element in dropdown*/
              dropzoneOptions: {
                autoProcessQueue : false,
                url: this.baseUrl+'conversation/send',
                acceptedFiles:'.ogv,.mov,.webm,.mp4,.ogg,.mp3,.oga,.wav,.jpeg,.jpg,.png',
                addRemoveLinks: true, 
                uploadMultiple:true,
                parallelUploads:5,
                dictRemoveFile: 'X (remove)',
                paramName: "upload_post",
                maxFilesize: 20, // MB
                maxFiles: 5,
                timeout: 180000,
              }
          };
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
        },
        methods: {
            sendingEvent (file, xhr, formData) {
              /*Event add additional paramether to dropzone request*/
              let group_id = null;
              if(typeof this.contact.groupdata != "undefined" && this.contact.groupdata != null){
                  group_id = this.contact.groupdata.id;
                }
              formData.append('contact_id', this.contact.id);
              formData.append('group_id', group_id);
              formData.append('text', this.message);
            },
            filesAdded(file){
              /*Event fires when Files added to the dropzone.*/
              this.isFileAddedDropzone = 1;
            },
            send() {
              this.isemptyContent = false;
              /*Function used to send request*/
              if (!this.contact.id) {
                 return;
              }
              
              if(this.isFileAddedDropzone){
                this.isLoading = true;
                this.$refs.myVueDropzone.dropzone.processQueue();
              }else if(this.message != ''){
                this.isLoading = true;
                /*fetch groupdata*/
                let group_id = null;
                if(typeof this.contact.groupdata != "undefined" && this.contact.groupdata != null){
                  group_id = this.contact.groupdata.id;
                }
                axios.post('/conversation/send', {
                    contact_id: this.contact.id,
                    group_id : group_id,
                    text: this.message
                }).then((response) => {
                  this.isLoading = false;
                  this.message = '';
                  this.$emit('send', response.data);
                });
              }else{
                this.isemptyContent = true;
                setTimeout(() => {
                  this.isemptyContent = false;
                },2000);
              }
              
              /*.then((response) => {
                console.log(response);
                  //this.$emit('send', response.data);
              });*/
              //this.$emit('send', this.message);
            },
            afterComplete(file) {
              /*event trigger when queue has been completely processed/ uploaded.*/
              //console.log('afterComplete');
              //console.log(file);
              this.isLoading = false;
              this.message = '';
              this.removeAllFiles();
            },
            removeAllFiles() {
              /*Remove all files from dropzone*/
              this.$refs.myVueDropzone.dropzone.removeAllFiles();
            },
            successUpload(files, response){
              this.isLoading = false;
              /*Fired if successfully file uploaded*/
              //console.log('successUpload');
              //console.log(response);
              this.$emit('send', response);
            },
            errorUpload(file){
              this.isLoading = false;
              /*File uploaded encountered an error.*/
              this.$notify({
                type: 'error',
                text: 'Something went wrong,please try again!!'
              });
            },
            toggleDropzone(){
              /*Toggle Dropzone*/
              this.isDropZonevisible = !this.isDropZonevisible;
            }
        },
    };
</script>
