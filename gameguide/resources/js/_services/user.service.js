import { BehaviorSubject } from 'rxjs';
import Config from '../config'
//import { authHeader } from '../_helpers';
import { requestOptions, handleResponse } from '../_helpers';
import axios from 'axios'

export const API_URL = Config.API_URL;
//const currentUserSubject = JSON.parse(localStorage.getItem('user'));
const currentUserSubject = new BehaviorSubject(JSON.parse(localStorage.getItem('user')));

export const userService = {
    login,
    logout,
    fetchProfile,
    updateProfile,
    uploadImage,
    dashboardApi,
    bookingDashboardApi,
    currentUser: currentUserSubject.asObservable(),
    get currentUserValue () { return currentUserSubject.value }
   /* register,
    getAll,
    getById,
    update,
    delete: _delete*/
};

function login(username, password) {
    return fetch(API_URL+'api/login', requestOptions.post({ username, password }))
        .then(handleResponse)
        .then(user => {
            let userDetail = user.user;
            // login successful if there's a jwt token in the response
            if (userDetail.token) {
                // store user details and jwt token in local storage to keep user logged in between page refreshes
                localStorage.setItem('user', JSON.stringify(userDetail));
                currentUserSubject.next(userDetail);
            }
            return userDetail;
        });
}

/*
*fetch user Profile
*/
function fetchProfile(){
    return fetch(API_URL+'api/user/get-profile',requestOptions.get())
    .then(handleResponse)
    .then(response => {
        return response;
    });
}

/*
*Update User Profile
*/
function updateProfile(updateForm){
    return fetch(API_URL+'api/user/update-profile', requestOptions.post(updateForm))
    .then(handleResponse)
    .then(response => {
        return response;
    });
}


/*Upload Photo*/
function uploadImage(media){
    
    return axios.post(API_URL+'api/user/update-profile',
                media,
                fileheaders()
            ).then(response => {
                return response.data;
        })
        .catch(handleResponse);
}

/*
*Dashboard Api
*/
function dashboardApi(){
    return fetch(API_URL+'api/admin/dashboard',requestOptions.get())
    .then(handleResponse)
    .then(response => {
        return response;
    });
}

/*
*Booking Dashboard API
*/
function bookingDashboardApi(){
    return fetch(API_URL+'api/admin/bookingDashboard',requestOptions.get())
    .then(handleResponse)
    .then(response => {
        return response;
    });
}

function logout() {
    // remove user from local storage to log user out
    localStorage.removeItem('user');
    currentUserSubject.next(null);
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

/*function register(user) {
    const requestOptions = {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(user)
    };

    return fetch(`${config.apiUrl}/users/register`, requestOptions).then(handleResponse);
}

function getAll() {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`${config.apiUrl}/users`, requestOptions).then(handleResponse);
}


function getById(id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`${config.apiUrl}/users/${id}`, requestOptions).then(handleResponse);
}

function update(user) {
    const requestOptions = {
        method: 'PUT',
        headers: { ...authHeader(), 'Content-Type': 'application/json' },
        body: JSON.stringify(user)
    };

    return fetch(`${config.apiUrl}/users/${user.id}`, requestOptions).then(handleResponse);
}

// prefixed function name with underscore because delete is a reserved word in javascript
function _delete(id) {
    const requestOptions = {
        method: 'DELETE',
        headers: authHeader()
    };

    return fetch(`${config.apiUrl}/users/${id}`, requestOptions).then(handleResponse);
}*/

/*function handleResponse(response) {
    return response.text().then(text => {
        const data = text && JSON.parse(text);
        if (!response.ok) {
            if (response.status === 402) {
                // auto logout if 401 response returned from api
                logout();
                location.reload(true);
            }

            const error = (data && data.message) || response.statusText;
            return Promise.reject(error);
        }
        return data;
    });
}*/