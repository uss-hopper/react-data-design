import {httpConfig} from "../misc/http-config";

export const getTweets = () => async (dispatch) => {
	const payload =  await httpConfig.get("/apis/tweet/");
	dispatch({type: "FETCH_TWEETS",payload : payload.data });
};