import { userService } from '../_services';
import axios from 'axios';

export const requestOptions = {
    get() {
        return {
            method: 'GET',
            ...headers()
        };
    },
    getbody(params){
        return {
            method: 'GET',
            ...headers(),
            params: JSON.stringify(params)
        };
    },
    post(body) {
        return {
            method: 'POST',
            ...headers(),
            body: JSON.stringify(body)
        };
    },
    patch(body) {
        return {
            method: 'PATCH',
            ...headers(),
            body: JSON.stringify(body)
        };
    },
    put(body) {
        return {
            method: 'PUT',
            ...headers(),
            body: JSON.stringify(body)
        };
    },
    delete() {
        return {
            method: 'DELETE',
            ...headers()
        };
    },
    filepost(media){
        //console.log('--'+media);
        return{
            method : 'POST',
            ...headers(),
            body:JSON.stringify(media)
        }
    }
}

export const headerOptions = {
    jsonHeader(){
        return headers();
    },
    fileHeader(){
        return fileheaders();
    },
    paramsHeaders(params){
        return paramsHeaders(params);
    }

}

function paramsHeaders(params) {
    const currentUser = userService.currentUserValue || {};
    const authHeader = currentUser.token ? { 'Authorization': 'Bearer ' + currentUser.token } : {}
    return {
        headers: {
            ...authHeader,
            'Content-Type': 'application/json',
        },
        params
    }
}

function headers() {
    const currentUser = userService.currentUserValue || {};
    const authHeader = currentUser.token ? { 'Authorization': 'Bearer ' + currentUser.token } : {}
    return {
        headers: {
            ...authHeader,
            'Content-Type': 'application/json'
        }
    }
}

function fileheaders() {
    const currentUser = userService.currentUserValue || {};
    const authHeader = currentUser.token ? { 'Authorization': 'Bearer ' + currentUser.token } : {}
    return {
        headers: {
            ...authHeader,
            'Content-Type': 'multipart/form-data'
        }
    }
}