import {Component} from "@angular/core";
import {SignInService} from "../../services/sign.in.service";
import {Status} from "../../interfaces/status";

@Component({
	selector: "main-nav",
	template: require("./main-nav.html")
})

export class MainNavComponent {
	status: Status = null;

	constructor(private signInService : SignInService) {}
	logOut() : void{
		this.signInService.signOut();
}

}