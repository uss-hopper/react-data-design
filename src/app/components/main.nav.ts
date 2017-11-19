import {Component} from "@angular/core";
import {SignInService} from "../services/sign.in.service";
import {Status} from "../classes/status";

@Component({
	selector: "main-nav",
	templateUrl: "./templates/main-nav.html",
})

export class MainNavComponent {
	status: Status = null;

	constructor(private signInService : SignInService) {}
	logOut() : void{
		this.signInService.signOut();
}

}