import { adminInboxService } from '../../_services';

const state = {
  all: [],
  loading : false,
  initConvLoading : false,
  contacts: {},
  messages: [],
  message: null,
  unreadmessage: [],
  selectedChat: '',
  updateNotification: 0,
  totalPages:null,
  totalItems:0,
  latestLoading: false,
  lastMessage : []

};

const actions = {
    getAllOwners({ dispatch,commit }) {
        commit('fetchOwners');
        adminInboxService.getAllOwners()
          .then(
              response => commit('fetchOwnersSuccess', response.owner),
              error => {
              	commit('fetchOwnersFailure', error)
              	setTimeout(() => {
                      let message = error;
                      if(typeof (error) == 'object'){
                          message = error.message;
                      }
                      dispatch('alert/error', message, { root: true });
                  })
              }
          );
    },
    getAllAdmins({ dispatch,commit }) {
        commit('fetchAdmins');
        adminInboxService.getAllAdmins()
          .then(
              response => commit('fetchAdminsSuccess', response.admins),
              error => {
              	commit('fetchAdminsFailure', error)
              	setTimeout(() => {
                      let message = error;
                      if(typeof (error) == 'object'){
                          message = error.message;
                      }
                      dispatch('alert/error', message, { root: true });
                  })
              }
          );
    },
    getConversation({ dispatch,commit }, params) {
        commit('fetchConversation',params.page);
        adminInboxService.getConversation(params)
          .then(
              response => commit('fetchConversationSuccess', response),
              error => {
              	commit('fetchConversationFailure', error)
              	setTimeout(() => {
                      let message = error;
                      if (typeof (error) == 'object') {
                          message = error.message;
                      }
                      dispatch('alert/error', message, { root: true });
                  })
              }
          );
    },
    getUnreadMessage({ dispatch,commit }) {
        commit('fetchUnreadMessage');
        adminInboxService.getUnreadMessage()
          .then(
              response => commit('fetchUnreadMessageSuccess', response.unreadmessage),
              error => {
                commit('fetchUnreadMessageFailure', error)
                setTimeout(() => {
                      let message = error;
                      if (typeof (error) == 'object') {
                          message = error.message;
                      }
                      dispatch('alert/error', message, { root: true });
                  })
              }
          );
    },
    selectedChatMessage({ commit }, chat){
      commit('setSelectedChat', chat);
    },
    updateNotificationMessage({ commit }, notify){
      commit('setNotificationStatus', notify);
    },
    countChange({dispatch,commit},countChange){
        commit('countStatusRequest',countChange);

        adminInboxService.unreadCountBtn(countChange)
            .then(
                response => {
                    commit('countStatusSuccess',response)
                    setTimeout(() => {
                        // display success message after route change completes
                        dispatch('alert/success', response.message, { root: true });
                    })
                },
                error => {
                    commit('countStatusFailure',error)
                    setTimeout(() => {
                        let message = error;
                        if(typeof (error) == 'object'){
                            message = error.message;
                        }
                        dispatch('alert/error', message, { root: true });
                    })
                }
            );

    },
    sendMessage({ dispatch,commit }, data) {
        commit('sendMessage');
        return adminInboxService.sendMessage(data)
          .then(
              response => commit('setMessages', response),
              error => {
              	commit('sendMessageFailure', error)
              	setTimeout(() => {
                      let message = error;
                      if (typeof (error) == 'object') {
                          message = error.message;
                      }
                      dispatch('alert/error', message, { root: true });
                  })
              }
          );
    },
    getLatestMessage({ dispatch,commit }) {
        commit('fetchLatestMessage');
        adminInboxService.getLatestMessage()
          .then(
              response => commit('fetchLatestMessageSuccess', response),
              error => {
                commit('fetchLatestMessageFailure', error)
              }
          );
    },

};

const mutations = {
    fetchOwners(state) {
        state.error = '' ;
        state.loading = true;
        state.contacts = {};
    },
	  fetchOwnersSuccess(state, owners) {
        state.loading = false;
        state.contacts = owners;
    },
    fetchOwnersFailure(state, error) {
        state.loading = false;
        state.error = error ;
        setTimeout(() => {
            state.error = '';
        },3000);
    },
    fetchAdmins(state) {
        state.error = '' ;
        state.loading = true;
        state.contacts = {};
    },
    setSelectedChat(state, chat){
      state.selectedChat = chat;

      setTimeout(() => {
        state.selectedChat = '';
      },3000);
    },
    setNotificationStatus(state, notify){
      state.updateNotification = notify;

      setTimeout(() => {
        state.updateNotification = 0;
      },3000);
    },
	  fetchAdminsSuccess(state, admins) {
        state.loading = false;
        state.contacts = admins;
    },
    fetchAdminsFailure(state, error) {
        state.loading = false;
        state.error = error ;
        setTimeout(() => {
            state.error = '';
        },3000);
    },
    fetchConversation(state,page) {
        state.error = '' ;
        state.loading = true;
        if(page == 1){
            state.messages = [];
            state.initConvLoading = true;
        }
    },
	  fetchConversationSuccess(state,response) {
        state.loading = false;
        state.initConvLoading = false;
        //state.messages = response.messages;
        response.messages.forEach(function(item){
            state.messages.push(item);
        })
        state.totalPages=  response.page_count;
        state.totalItems = response.message_count;
    },
    fetchConversationFailure(state, error) {
        state.loading = false;
        state.initConvLoading = false;
        state.error = error ;
        setTimeout(() => {
            state.error = '';
        },3000);
    },
    fetchUnreadMessage(state) {
        state.error = '' ;
        state.loading = true;
        state.unreadmessage = [];
    },
    fetchUnreadMessageSuccess(state, unreadmessage) {
        state.loading = false;
        state.unreadmessage = unreadmessage;
    },
    fetchUnreadMessageFailure(state, error) {
        state.loading = false;
        state.error = error ;
        setTimeout(() => {
            state.error = '';
        },3000);
    },
    resetConversationMessages(state) {
        state.messages = [];
    },
    resetComposeMessage(state) {
        state.message = null;
    },
    sendMessage(state) {
        state.error = '' ;
        state.loading = true;
        state.message = null;
    },
    setMessages(state, message) {
        state.loading = false;
    },
    countStatusRequest(state,countChange){
        state.error = '' ;
        state.loading = true;
      
    },
    countStatusSuccess(state,response){
        state.loading = false;
        state.error = '' ;
        
    },
    countStatusFailure(state,error){
        state.loading = false;
        state.error = error ;
        
    },
    sendMessageFailure(state, error) {
        state.loading = false;
        state.error = error ;
        setTimeout(() => {
            state.error = '';
        },3000);
    },
    fetchLatestMessage(state){
      state.latestLoading = true;
    },
    fetchLatestMessageSuccess(state,message){
      state.latestLoading = false;
      state.lastMessage = message;
    },
    fetchUnreadMessageFailure(state,error){
      state.latestLoading = false;
    }
};

export const inbox = {
    namespaced: true,
    state,
    actions,
    mutations
};
