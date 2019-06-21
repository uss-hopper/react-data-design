import {httpConfig} from "../utils/http-config";

export const getAllTweets = (test) => async (dispatch) => {
	const payload =  await httpConfig.get("/apis/tweet/");
	dispatch({type: "FETCH_TWEETS",payload : payload.data });
};