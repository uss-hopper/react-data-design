import {Injectable} from "@angular/core";
import {Status} from "../interfaces/status";
import {Observable} from "rxjs/internal/Observable";
import {SignUp} from "../interfaces/sign.up";
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