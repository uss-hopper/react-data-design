import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Status} from "../classes/status";

@Injectable()


export class SessionService {


	 constructor(protected http:HttpClient) {}

	 private sessionUrl = "api/earl-grey/";

	 setSession() {
		 this.http.get<Status>(this.sessionUrl);
	}

}