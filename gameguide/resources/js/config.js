// vue.config.js

//alert(base_url)
var env = process.env.NODE_ENV || 'development'

export default {
	BASE_URL: process.env.MIX_APP_URL,
	API_URL: process.env.MIX_APP_URL,
	//API_URL: `${process.env.MIX_APP_URL}api/v1/`,

}
