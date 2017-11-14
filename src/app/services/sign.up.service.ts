import {Injectable} from "@angular/core";
import {Status} from "../classes/status";
import {Observable} from "rxjs/Observable";
import {SignUp} from "../classes/sign.up";
import {HttpClient} from "@angular/common/http";

@Injectable()
export class SignUpService {
	constructor(protected http: HttpClient) {

	}

	private signUpUrl = "api/sign-up/";

	createProfile(signUp: SignUp) : Observable<Status> {
		return(this.http.post<Status>(this.signUpUrl, signUp));
	}
}