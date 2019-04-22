import {httpConfig} from "./http-config";

export function tweet() {
	const endPoint = "/apis/tweet/";
	return {
		getTweetByTweetId: (id) => httpConfig.get(endPoint+id),
		getTweetByTweetProfileId: (tweetProfileId) => httpConfig.get(`${endPoint}?tweetProfileId=${tweetProfileId}`),
		getTweetByTweetContent: (tweetContent) => httpConfig.get(`${endPoint}?tweetContent=${tweetContent}`),
		getAllTweets: () =>  httpConfig.get(endPoint),
		postTweet: (object) => httpConfig.post(endPoint, object),
		editTweet: (id, object) => httpConfig.put(endPoint + id, object),
		deleteTweet: (id) => httpConfig.delete(endPoint + id)
	}
}



