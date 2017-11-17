import {Injectable} from "@angular/core";
import {HttpClient, HttpResponse} from "@angular/common/http";
import {Observable} from "rxjs/Observable";

@Injectable()
export class SessionService {

	constructor(protected http:HttpClient) {}

	private sessionUrl = "apis/session/";

	setSession() : Observable<HttpResponse<any>> {
		return (this.http.head(this.sessionUrl)
			.map((response : HttpResponse<any>) => response));
	}
}