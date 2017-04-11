import {Injectable} from "@angular/core";
import {Http} from "@angular/http";
import {BaseService} from "./base-service";
import {Status} from "../classes/status";
import {Profile} from "../classes/profile";
import {Observable} from "rxjs/Observable";

@Injectable ()
export class ProfileService extends BaseService {

	constructor(protected http:Http) {
		super(http);
	}

	//define the API endpoint
	private profileUrl = "api/profile/";

	//reach out to the profile  API and delete the profile in question
	deleteProfile(id: number) : Observable<Status> {
		return(this.http.delete(this.profileUrl + id)
			.map(BaseService.extractMessage)
			.catch(BaseService.handleError));
	}

	//call to the profile API and create the profile
	createProfile(profile: Profile) : Observable<Status> {
		return(this.http.post(this.profileUrl, profile)
			.map(BaseService.extractMessage)
			.catch(BaseService.handleError));
	}

	// call the the Profile API and create
}
