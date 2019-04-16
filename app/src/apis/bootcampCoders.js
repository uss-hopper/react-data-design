import axios from "axios";

export default axios.create({
	baseURL: "/apis",
	headers: {
		"Accept" : "application/json",
		'Content-Type': 'application/json'
	}
})