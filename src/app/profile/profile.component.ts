import {Component, OnInit} from "@angular/core";
import {Status} from "../shared/interfaces/status";
import {Profile} from "../shared/interfaces/profile";
import {JwtHelperService} from "@auth0/angular-jwt";
import {ProfileService} from "../shared/services/profile.service";



@Component({
	template:`
	<h1>{{profile.profileAtHandle}}</h1>
	
	`
})
export class ProfileComponent implements OnInit{

	profile: Profile;
	status: Status;

	constructor(private profileService: ProfileService, private jwtHelper : JwtHelperService) {}

	ngOnInit(): void {
		this.currentlySignedIn()
	}

	currentlySignedIn() : void {

		const decodedJwt = this.jwtHelper.decodeToken(localStorage.getItem('jwt-token'));

		this.profileService.getProfile(decodedJwt.auth.profileId)
			.subscribe(profile => this.profile = profile)

	}


}