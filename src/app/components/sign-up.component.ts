/*
 this component is for signing up to use the site.
 */

//import needed modules for the sign-up component
import {Component, ViewChild,} from "@angular/core";
import {Observable} from "rxjs/Observable"
import {Router} from "@angular/router";
import {Status} from "../classes/status";
import {SignUpService} from "../services/sign-up.service";
import {SignUp} from "../classes/sign-up";
import {setTimeout} from "timers";

//declare $ for good old jquery
declare let $: any;

// set the template url and the selector for the ng powered html tag

@Component({
	templateUrl: "./templates/sign-up.php",
	selector: "sign-up"
})
export class SignUpComponent {

	//
	@ViewChild("signUpForm") signUpForm: any;
	signUp: SignUp = new SignUp(null, null, null, null, null);
	status: Status = null;

	constructor(private signUpService: SignUpService, private router: Router) {
	}

	createSignUp(): void {
		this.signUpService.createProfile(this.signUp)

			.subscribe(status => {
				console.log(this.signUp);

				console.log(this.status);
				if(status.status === 200) {
					alert(status.message);
					this.signUpForm.reset();
					setTimeout(function() {
						$("#signUp-modal").modal('hide');
					}, 500);
					this.router.navigate([""]);
				}
			});
	}
}





