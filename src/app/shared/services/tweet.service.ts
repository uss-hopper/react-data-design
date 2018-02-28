import {Injectable} from "@angular/core";

import {Status} from "../classes/status";
import {Tweet} from "../classes/tweet";
import {Observable} from "rxjs/Observable";
import {HttpClient} from "@angular/common/http";

@Injectable ()
export class TweetService {

	constructor(protected http : HttpClient ) {}

	//define the API endpoint
	private tweetUrl = "api/tweet/";

	// call to the tweet API and delete the tweet in question
	deleteTweet(tweetId: number) : Observable<Status> {
		return(this.http.delete<Status>(this.tweetUrl + tweetId));

	}

	// call to the tweet API and edit the tweet in question
	editTweet(tweet : Tweet) : Observable<Status> {
		return(this.http.put<Status>(this.tweetUrl + tweet.tweetId, tweet));
	}

	// call to the tweet API and create the tweet in question
	createTweet(tweet : Tweet) : Observable<Status> {
		return(this.http.post<Status>(this.tweetUrl, tweet));
	}

	// call to the tweet API and get a tweet object based on its Id
	getTweet(tweetId : number) : Observable<Tweet> {
		return(this.http.get<Tweet>(this.tweetUrl + tweetId));

	}

	// call to the API and get an array of tweets based off the profileId
	getTweetbyProfileId(tweetProfileId : number) : Observable<Tweet[]> {
		return(this.http.get<Tweet[]>(this.tweetUrl + tweetProfileId));

	}

	// call to tweet API and get an array of tweets based off the tweetContent
	getTweetByContent(tweetContent : string) : Observable<Tweet[]> {
		return(this.http.get<Tweet[]>(this.tweetUrl + tweetContent));

	}

	//call to the API and get an array of all the tweets in the database
	getAllTweets() : Observable<Tweet[]> {
		return(this.http.get<Tweet[]>(this.tweetUrl));

	}




}