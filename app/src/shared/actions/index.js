import {httpConfig} from "../misc/http-config";

export const getAllTweets = () => async dispatch => {
	const {data} = await httpConfig("/apis/tweet/");
	dispatch({type: "GET_ALL_TWEETS", payload: data })
};

