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
	private tweetApi = "api/tweet/";

	//reach out to tweet API and delete the tweet in question
	deleteTweet(id: number) : Observable<Status> {
		return(this.http.delete(this.tweetApi + id)
			.map(BaseService.extractMessage)
			.catch(BaseService.handleError));s
	}
}