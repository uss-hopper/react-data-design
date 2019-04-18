import axios from "axios";
const http = axios.create({
	baseURL: "/apis",
});
http.interceptors.response.use(function (response) {

	console.log(response);
	// Do something with response data
	return response.data;
}, function (error) {
	// Do something with response error
	return Promise.reject(error);
});
export default http