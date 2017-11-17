import {Component, OnInit} from "@angular/core";
import {TweetService} from "../services/tweet.service";
import {Status} from "../classes/status";
import {Tweet} from "../classes/tweet";
import {ProfileService} from "../services/profile.service";
import {Profile} from "../classes/profile";
import {Params} from "@angular/router";
import {subscribeToResult} from "rxjs/util/subscribeToResult";


@Component({
	selector: "list-tweet",
	templateUrl: "./templates/list-tweets.html"
})

export class ListTweetsComponent implements OnInit{

	//declare needed state variables for latter use
	status: Status = null;

	tweet: Tweet = new Tweet(null,null,null,null);
	tweets: Tweet[] = [];


	profile: Profile = new Profile(null,null,null,null,null);



	constructor(private tweetService: TweetService, private profileService: ProfileService) {}

	//life cycling before my eyes
	ngOnInit() : void {
		this.listTweets()
	}

	getTweetProfile(): void {
		this.profileService.getProfile(this.tweet.tweetProfileId)
	}


	listTweets(): void {
		this.tweetService.getAllTweets()
			.subscribe(tweets => this.tweets = tweets);

		for (this.tweet of this.tweets) {

				console.log("fuck you tom wu");

		}
	}


}