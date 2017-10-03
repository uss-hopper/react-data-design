import {Component} from "@angular/core";
import {TweetService} from "../services/tweet.service";
import {Status} from "../classes/status";
import{Tweet} from "../classes/tweet";



@Component({
	selector: "create-tweet",
	templateUrl: "./templates/create-tweet.html"
})

export class CreateTweetComponent {

	//declare needed state variables for later use.
	status: Status = null;

	tweet: Tweet = new Tweet(null,null,null,null);

	constructor(private tweetService : TweetService) {}


	createTweet(): void  {
		this.tweetService.createTweet(this.tweet)
			.subscribe(status => this.status = status);
	}
}
