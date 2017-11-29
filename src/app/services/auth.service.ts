
import { JwtHelperService } from '@auth0/angular-jwt';
import {Injectable} from "@angular/core";


@Injectable()

export class AuthenticationService {

	token: string = this.jwtHelperService.tokenGetter();

	constructor(private jwtHelperService: JwtHelperService) {}

	loggedIn() {

		if (!this.token) {
			return false;
		}

		const tokenExpired: boolean = this.jwtHelperService.isTokenExpired(token);

		return !tokenExpired
	}

	decodeJwt() : any {
		let isLoggedIn : boolean = this.loggedIn();

		if (!isLoggedIn) {
			return false;
		}
		const authObject = this.jwtHelperService.decodeToken(this.token);

		console.log(authObject);

		return authObject;
	}

}
