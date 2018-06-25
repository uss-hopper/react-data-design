import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Status} from "../classes/status";
import {Observable} from "rxjs/internal/Observable";
@Injectable()


export class SessionService {


	 constructor(protected http:HttpClient) {}

	 private sessionUrl = "api/earl-grey/";

	 setSession() {
		return (this.http.get<Status>(this.sessionUrl, {}));

	}

}