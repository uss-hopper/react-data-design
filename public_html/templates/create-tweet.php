

<div class="form-group">
	<input id="tweetContent" name="tweetContent" type="text" class="form-control input-lg"
			 [(ngModel)]=tweet.tweetContent (submit)="createTweet(); maxlength= "140">
	<button name="submit" type="submit" class="btn btn-success"><i class="fa fa-code"></i></button>
</div>

<div *ngIf=" tweetContent.errors && (tweetContent.dirty || tweetContent.touched)" class="alert alert-danger">
	<div [hidden] = "!tweetContent.errors.maxlength">
		tweet cannot be larger than 140 characters
	</div>

</div>

