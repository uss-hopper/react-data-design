import {Component, OnInit} from "@angular/core";
import {TweetService} from "../services/tweet.service";
import {Status} from "../classes/status";
import {Tweet} from "../classes/tweet";

@Component({
	selector: "list-tweet",
	templateUrl: ".templates/list-tweets.php"
})

export class ListTweetsComponent implements OnInit{

	//declare needed state variables for latter use
	status: Status = null;

	tweet: Tweet = new Tweet(null,null,null,null);
	tweets: Tweet[] = [];

	constructor(private tweetService: TweetService) {}

	//life cycling before my eyes
	ngOnInit() : void {
		this.listTweets()
	}


	listTweets(): void {
		this.tweetService.getAlltweets()
			.subscribe(tweets => this.tweets = tweets);

	}
}