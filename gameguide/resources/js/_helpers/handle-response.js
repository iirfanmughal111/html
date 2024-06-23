import { userService } from '../_services';

export function handleResponse(response) {
    if(typeof response.response != 'undefined' && response.response != null && response.response != ''){ 
        let response1 = response.response;
        let data = response1.data;
        
        if (!response1.ok) {
            if ([401, 403].indexOf(response1.status) !== -1) {
                // auto logout if 401 Unauthorized or 403 Forbidden response returned from api
                userService.logout();
                location.reload(true);
            }
            let error = response1.statusText;
            if((data && data.message) || (data && data.error)){
                error = data;
            }
            //const error = (data && data.message) || (data && data.error) || response.statusText;
            return Promise.reject(error);
        }

        return data;
        
    }else{
        return response.text().then(text => {
            const data = text && JSON.parse(text);
            if (!response.ok) {
                if ([401, 403].indexOf(response.status) !== -1) {
                    // auto logout if 401 Unauthorized or 403 Forbidden response returned from api
                    userService.logout();
                    location.reload(true);
                }
                let error = response.statusText;
                if((data && data.message) || (data && data.error)){
                    error = data;
                }
                //const error = (data && data.message) || (data && data.error) || response.statusText;
                return Promise.reject(error);
            }

            return data;
        });
    }
    
}