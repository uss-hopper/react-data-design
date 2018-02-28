/*
 this component is for signing up to use the site.
 */

//import needed modules for the sign-up component
import {Component, OnInit, ViewChild,} from "@angular/core";
import {Observable} from "rxjs/Observable"
import {Router} from "@angular/router";
import {Status} from "../shared/classes/status";
import {SignUp} from "../shared/classes/sign.up";
import {setTimeout} from "timers";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {SignUpService} from "../shared/services/sign.up.service";

//declare $ for good old jquery
declare let $: any;

// set the template url and the selector for the ng powered html tag

@Component({
	template: require
	("./sign-up.component.html"),
	selector: "sign-up"
})
export class SignUpComponent implements OnInit{

	//
	signUpForm : FormGroup;

	signUp: SignUp = new SignUp(null, null, null, null, null);
	status: Status = null;


	constructor(private formBuilder : FormBuilder, private router: Router, private signUpService: SignUpService) {}

	ngOnInit()  : void {
		this.signUpForm = this.formBuilder.group({
			atHandle: ["", [Validators.maxLength(32), Validators.required]],
			email: ["", [Validators.maxLength(128), Validators.required]],
			phoneNumber: ["", [Validators.maxLength(32)]],
			password:["", [Validators.maxLength(48), Validators.required]],
			passwordConfirm:["", [Validators.maxLength(48), Validators.required]]

		});

}

	createSignUp(): void {

		let signUp =  new SignUp(this.signUpForm.value.atHandle, this.signUpForm.value.email, this.signUpForm.value.password, this.signUpForm.value.passwordConfirm, this.signUpForm.value.phoneNumber);

		this.signUpService.createProfile(signUp)
			.subscribe(status => {
				this.status = status;

				if(this.status.status === 200) {
					alert(status.message);
					setTimeout(function() {
						$("#signUp-modal").modal('hide');
					}, 500);
					this.router.navigate([""]);
				}
			});
	}
}





