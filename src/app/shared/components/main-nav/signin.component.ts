//this component controls the sign-in modal when "sign-in" is clicked

import {Component, ViewChild, EventEmitter, Output} from "@angular/core";



import {Router} from "@angular/router";
import {Observable} from "rxjs/Observable"
import {Status} from "../../interfaces/status";

import {SignIn} from "../../interfaces/sign.in";
import {CookieService} from "ng2-cookies";
import {SignInService} from "../../services/sign.in.service";
declare var $: any;

@Component({
	template: require( "./signin.html"),
	selector: "signin"
})

export class SignInComponent {
	@ViewChild("signInForm") signInForm: any;

	signin: SignIn = {profileEmail: null, profilePassword: null};
	status: Status = {status: null, type: null, message: null};
	//cookie: any = {};

	constructor(private SignInService: SignInService, private router: Router, private cookieService : CookieService) {
	}



	signIn(): void {
		localStorage.removeItem("jwt-token");
		this.SignInService.postSignIn(this.signin).subscribe(status => {
				this.status = status;


				if(status.status === 200) {

					this.router.navigate(["profile-page"]);
					//location.reload(true);
					this.signInForm.reset();
					setTimeout(1000,function(){$("#signin-modal").modal('hide');});
				} else {
					console.log("failed login")
				}
			});
	}
}
