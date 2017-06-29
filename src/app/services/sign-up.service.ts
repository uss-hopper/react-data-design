import {Injectable} from "@angular/core";
import {Http} from "@angular/http";
import {BaseService} from "./base.service";
import {Status} from "../classes/status";
import {Profile} from "../classes/profile";
import {Observable} from "rxjs/Observable";
import {SignUp} from "../classes/sign-up";

@Injectable()
export class SignUpService extends BaseService {
	constructor(protected http: Http) {
		super(http);
	}

	private signUpUrl = "api/sign-up";

	createProfile(signUp: SignUp) : Observable<Status> {
		return(this.http.post(this.signUpUrl, signUp)
			.map(this.extractMessage)
			.catch(this.handleError));
	}
}