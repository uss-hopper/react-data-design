

<div class="form-group">
	<input id="tweetContent" name="tweetContent" type="text" class="form-control input-lg"
			 [(ngModel)]=tweet.tweetContent (submit)="createTweet()">
	<button name="submit" type="submit" class="btn btn-success"><i class="fa fa-code"></i></button>
</div>

<div *ngIf="status !== null" class="alert alert-dismissible" [ngClass]="status.type" role="alert">
	<button type="button" class="close" aria-label="Close" (click)="status = null;"><span aria-hidden="true">&times;</span></button>
	{{ status.message }}
</div>



