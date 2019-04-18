import axios from "axios";

const http = axios.create({
	baseURL: "/apis",
});

http.interceptors.response.use(function ({data} ) {
	if(data.status === 200) {
		return data.data !== null
			? {message: null, data: data.data, status: 200, type: "alert-success"}
			: {message: data.message, status: 200, type: "alert-success", data: null};
	}
	return {message: data.message, status: data.status, type: "alert-danger", data: null}

}, function (error) {
	// Do something with response error
	console.log(error);
	return Promise.reject(error);
});
export default http;