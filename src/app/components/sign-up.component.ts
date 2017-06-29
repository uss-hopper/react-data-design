/*
this component is for signing up to use the site.
 */

//import needed modules for the sign-up component
import {Component, ViewChild, } from "@angular/core";
import {Observable} from "rxjs/Observable"
import {Profile} from "../classes/profile";
import {Status} from "../classes/status";
import {SignUpService} from "../services/sign-up.service";
import {SignUp} from "../classes/sign-up";

//declare $ for good old jquery
declare let $ : any;

// set the template url and the selector for the ng powered html tag

@Component ({
	templateUrl: "./template/sign-up.php",
	selector: "sign-up"
})
export class SignUpComponent {

}





