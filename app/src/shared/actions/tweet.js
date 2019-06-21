import {useAxios} from "../hooks/useAxios";

const axios = useAxios();

export const getAllTweets = (test) => async (dispatch) => {
	const payload =  axios.get("/apis/tweet/");
	dispatch({type: "GET_ALL_TWEETS",payload : payload.data });
};