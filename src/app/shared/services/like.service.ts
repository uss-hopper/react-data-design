import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Status} from "../classes/status";
import {Like} from "../classes/like";
import {Observable} from "rxjs/Observable";

@Injectable ()
export class LikeService {

	constructor(protected http : HttpClient ) {

	}

	//define the API endpoint
	private likeUrl = "/api/like/";


	//call the like API and create a new like
	createLike(like : Like) : Observable<Status> {
		return (this.http.post<Status>(this.likeUrl, like));
	}

	//grabs a  like based on its composite key
	getLikeByCompositeKey(likeProfileId : number, likeTweetId : number) : Observable <Like> {
		return (this.http.get<Like>(this.likeUrl+ "?likeProfileId=" + likeProfileId +"&likeTweetId=" + likeTweetId))
	}

	getLikeBytweetId (likeTweetId : number) : Observable<Like[]> {
	return(this.http.get<Like[]>(this.likeUrl + likeTweetId))
	}


}