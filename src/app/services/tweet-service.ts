import {Injectable} from "@angular/core";
import {Http} from "@angular/http";
import {BaseService} from "./base-service";
import {Status} from "../classes/status";
import {Tweet} from "../classes/Tweet";
import {Observable} from "rxjs/Observable";

@Injectable ()
export class TweetService extends BaseService {

	constructor(protected http:Http ) {
		super(http);
	}

	//define the API endpoint
	private tweetUrl = "api/tweet/";

	// call to the tweet API and delete the tweet in question
	deleteTweet(tweetId: number) : Observable<Status> {
		return(this.http.delete(this.tweetUrl + tweetId)
			.map(BaseService.extractMessage)
			.catch(BaseService.handleError));
	}

	// call to the tweet API and edit the tweet in question
	editTweet(tweet : Tweet) : Observable<Status> {
		return(this.http.put(this.tweetUrl + tweet.tweetId, tweet)
			.map(BaseService.extractMessage)
			.catch(BaseService.handleError));
	}

	// call to the tweet API and create the tweet in question
	createTweet(tweet : Tweet) : Observable<Status> {
		return(this.http.post(this.tweetUrl, tweet)
			.map(BaseService.extractMessage)
			.catch(BaseService.handleError));
	}

	// call to the tweet API and get a tweet object based on its Id
	getTweet(tweetId : number) : Observable<Tweet> {
		return(this.http.get(this.tweetUrl + tweetId)
			.map(BaseService.extractData)
			.catch(BaseService.handleError));
	}

	// call to the API and get an array of tweets based off the profileId
	getTweetbyProfileId(tweetProfileId : number) : Observable<Tweet[]> {
		return(this.http.get(this.tweetUrl + tweetProfileId)
			.map(BaseService.extractData)
			.catch(BaseService.handleError));
	}

	// call to tweet API and get an array of tweets based off the tweetContent
	getTweetByContent(tweetContent : string) : Observable<Tweet[]> {
		return(this.http.get(this.tweetUrl +tweetContent)
			.map(BaseService.extractData)
			.catch(BaseService.handleError));
	}

	//call to the API and get an array of all the tweets in the database
	getAlltweets() : Observable<Tweet[]> {
		return(this.http.get(this.tweetUrl)
			.map(BaseService.extractData)
			.catch(BaseService.extractData));
	}




}