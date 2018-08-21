import {Component, OnInit} from "@angular/core";


import {Status} from "../shared/interfaces/status";
import {Tweet} from "../shared/interfaces/tweet";

import {Profile} from "../shared/interfaces/profile";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";

import {Like} from "../shared/interfaces/like";
import {AuthService} from "../shared/services/auth.service";
import {ProfileService} from "../shared/services/profile.service";
import {LikeService} from "../shared/services/like.service";
import {TweetService} from "../shared/services/tweet.service";


@Component({
	selector: "list-tweet",
	template: require("./home.list.component.html")
})

export class ListTweetsComponent implements OnInit {

	createTweetForm: FormGroup;

	tweet: Tweet;


	profile : Profile;

	//declare needed state variables for latter use
	status: Status = null;

	tweets: Tweet[] = [];


	constructor(private authService: AuthService, private formBuilder: FormBuilder, private profileService: ProfileService, private likeService: LikeService, private tweetService: TweetService) {
	}

	//life cycling before my eyes
	ngOnInit(): void {
		this.listTweets();

		this.createTweetForm = this.formBuilder.group({
			tweetContent: ["", [Validators.maxLength(140), Validators.minLength(1), Validators.required]]
		});
	}

	getTweetProfile(): void {
		this.profileService.getProfile(this.tweet.tweetProfileId)
	}


	listTweets(): void {
		this.tweetService.getAllTweets()
			.subscribe(tweets => this.tweets = tweets);
	}

	createTweet(): void {

		let tweet: Tweet;

		this.tweetService.createTweet(tweet)
			.subscribe(status => {
				this.status = status;
				if(this.status.status === 200) {
					this.createTweetForm.reset();
					alert(this.status.message);
					this.listTweets();
				}
			});
	}
}