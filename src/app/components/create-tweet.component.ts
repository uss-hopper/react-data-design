import {Component} from "@angular/core";
import {TweetService} from "../services/tweet.service";
import {Status} from "../classes/status";
import{Tweet} from "../classes/tweet";



@Component({
	selector: "create-tweet",
	template: `
		
		
		<form id="new-tweet" name="new-tweet" #new-tweet = "ngForm" (submit)="createTweet();" novalidate>
		<div class="form-group">
			<span>
				<input id="tweetContent" name="tweetContent" type="text" class="form-control input-lg" [(ngModel)] =tweet.tweetContent>
				<button name="submit" type="submit" class="btn btn-success"><i class="fa-code" aria-hidden="true"></i></button>
			</span>
		</div>
	</form>
	`
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
