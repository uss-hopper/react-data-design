import {Injectable} from "@angular/core";
import {Http} from "@angular/http";
import {BaseService} from "./base.service";
import {Status} from "../classes/status";
import {Like} from "../classes/like";
import {Observable} from "rxjs/Observable";

@Injectable ()
export class LikeService extends BaseService {

	constructor(protected http:Http ) {
		super(http);
	}

	//define the API endpoint
	private likeUrl = "/api/like/";

	//call to the like API and delete the like in question
	deleteLike(like :Like) : Observable<Status> {
		return(this.http.put(this.likeUrl + like.likeProfileId +like.likeTweetId, like)
			.map(this.extractData)
			.catch(this.handleError));
	}

	//call the like API and create a new like
	createLike(like : Like) : Observable<Status> {
		return(this.http.post(this.likeUrl, like)
			.map(this.extractMessage)
			.catch(this.handleError));
	}

	//grabs a spcfic like based on its composite key
	getLikeByCompositeKey(likeProfileId : number, likeTweetId : number) : Observable <Like> {
		return(this.http.get(this.likeUrl + likeProfileId + likeTweetId)
			.map(this.extractData)
			.catch(this.handleError));
	}

	getLikeBytweetId (likeTweetId : number ) : Observable <Like[]> {
		return(this.http.get(this.likeUrl + likeTweetId)
			.map(this.extractData)
			.catch(this.handleError));
	}


}