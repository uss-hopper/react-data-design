//import needed @angularDependencies
import {RouterModule, Routes} from "@angular/router";


//import all needed components
import {HomeComponent} from "./home/home.component";
import {ListTweetsComponent} from "./home/home.list.component";
import {MainNavComponent} from "./shared/components/main-nav/main.nav";
import {ProfileComponent} from "./profile/profile.component";
import {SignInComponent} from "./shared/components/main-nav/signin.component";
import {SignUpComponent} from "./sign-up/sign.up.component";


// import all needed Services


import {CookieService} from "ng2-cookies";
import {JwtHelperService} from "@auth0/angular-jwt";


//import all needed Interceptors
import {APP_BASE_HREF} from "@angular/common";
import {HTTP_INTERCEPTORS} from "@angular/common/http";
import {AuthGuardService} from "./shared/guards/auth.guard";
import {AuthService} from "./shared/services/auth.service";
import {LikeService} from "./shared/services/like.service";
import {ProfileService} from "./shared/services/profile.service";
import {SessionService} from "./shared/services/session.service";
import {SignInService} from "./shared/services/sign.in.service";
import {SignUpService} from "./shared/services/sign.up.service";
import {TweetService} from "./shared/services/tweet.service";
import {DeepDiveInterceptor} from "./shared/interceptors/deep.dive.interceptor";








//an array of the components that will be passed off to the module
export const allAppComponents = [ HomeComponent, ListTweetsComponent,MainNavComponent, ProfileComponent, SignInComponent, SignUpComponent];

//an array of routes that will be passed of to the module
export const routes: Routes = [
	{path: "profile-page", component: ProfileComponent, canActivate: [AuthGuardService]},
	{path: "", component: HomeComponent}


];

// an array of services

const services : any[] = [AuthService, AuthGuardService, CookieService,JwtHelperService ,LikeService, ProfileService, SessionService, SignInService,  SignUpService, TweetService];

// an array of misc providers
const providers : any[] = [
	{provide: APP_BASE_HREF, useValue: window["_base_href"]},
	{provide: HTTP_INTERCEPTORS, useClass: DeepDiveInterceptor, multi: true}

];

export const appRoutingProviders: any[] = [providers, services];

export const routing = RouterModule.forRoot(routes);