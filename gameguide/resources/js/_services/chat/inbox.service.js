import Config from '../../config'
import { requestOptions, handleResponse , headerOptions} from '../../_helpers';
import axios from 'axios'
export const API_URL = Config.API_URL;

export const adminInboxService = {
	getAllOwners,
	getConversation,
	sendMessage,
	getAllAdmins,
    getUnreadMessage,
    unreadCountBtn,
    getLatestMessage
}


function getAllOwners() {
    const url = API_URL+'api/admin/owners';
    return fetch(url, requestOptions.get())
    .then(handleResponse)
    .then(response => {
            return response;
        });
}

function getAllAdmins() {
    const url = API_URL+'api/admin/admins';
    return fetch(url, requestOptions.get())
    .then(handleResponse)
    .then(response => {
            return response;
        });
}

function getConversation(params) {
    let url = `${API_URL}conversation/${params.id}`;
    
    if(typeof (params.page) != 'undefined' && (params.page) != null && (params.page) != ''){
        url +='?page='+params.page
    }
	return fetch(url, requestOptions.get())
    .then(handleResponse)
    .then(response => {
      	return response;
     });
}

function sendMessage(data) {
    const url = `${API_URL}conversation/send`;
		return fetch(url, requestOptions.post(data))
    .then(handleResponse)
    .then(response => {function getConversation(contactId) {
    return fetch(url, requestOptions.get())
    .then(handleResponse)
    .then(response => {
        return response;
     });
}
      	return response;
     });
}

function getUnreadMessage() {
   // console.log('hgccty');
    const url = `${API_URL}notification/message`;
        return fetch(url, requestOptions.get())
    .then(handleResponse)
    .then(response => {
        return response;
     });
}

function unreadCountBtn(countChange) {
    /*check countChange is obj or single data*/
    let url = `${API_URL}unreadcount/${countChange}`;
    if(typeof countChange.type != "undefined" && countChange.type != null){
        url = `${API_URL}unreadcount/${countChange.id}/${countChange.type}`;
    }
        return fetch(url, requestOptions.get())
    .then(handleResponse)
    .then(response => {
        return response;
     });
}

function getLatestMessage(){
    const url = `${API_URL}getLatestMessage`;
        return fetch(url, requestOptions.get())
    .then(handleResponse)
    .then(response => {
        return response;
     });

}