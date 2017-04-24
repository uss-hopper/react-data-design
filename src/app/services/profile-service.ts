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

	// call to the Profile API and edit the profile in question
	editProfile(profile: Profile) : Observable<Status> {
		return(this.http.put(this.profileUrl + profile.profileId, profile)
			.map(BaseService.extractMessage)
			.catch(BaseService.handleError));
	}

	// call to the Profile API and get a Profile object by its id
	getProfile(id: number) : Observable<Profile> {
		return(this.http.get(this.profileUrl + id)
			.map(BaseService.extractData)
			.catch(BaseService.handleError));
	}

	// call to the API to grab an array of profiles based on the user input
	getProfileByProfileAtHandle(profileAtHandle: string) :Observable<Profile[]> {
		return (this.http.get(this.profileUrl + profileAtHandle)
			.map(BaseService.extractData)
			.catch(BaseService.handleError));
	}

	//call to the profile API and grab the corresponding profile by its email
	getProfileByProfileEmail(profileEmail: string) :Observable<Profile> {
		return(this.http.get(this.profileUrl + profileEmail)
			.map(BaseService.extractData)
			.catch(BaseService.handleError));
	}
}
