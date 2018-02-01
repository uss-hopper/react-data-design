//import needed @angularDependencies
import {RouterModule, Routes} from "@angular/router";
import {AuthGuardService} from './services/auth.guard.service';

//import all needed components
import {CreateTweetComponent} from "./components/create.tweet.component";
import {HomeComponent} from "./components/home/home.component";
import {ListTweetsComponent} from "./components/home/home.list.component";
import {MainNavComponent} from "./shared/components/main-nav/main.nav";
import {ProfileComponent} from "./components/profile/profile.component";
import {SignInComponent} from "./shared/components/main-nav/signin.component";
import {SignUpComponent} from "./components/home/sign-up/sign.up.component";


// import all needed Services

import {AuthService} from "./services/auth.service";

import {CookieService} from "ng2-cookies";
import {JwtHelperService} from "@auth0/angular-jwt";
import {LikeService} from "./services/like.service";
import {ProfileService} from "./services/profile.service";
import {SessionService} from "./services/session.service";
import {SignInService} from "./services/sign.in.service";
import {SignUpService} from "./services/sign.up.service";
import {TweetService} from "./services/tweet.service";

//import all needed Interceptors
import {APP_BASE_HREF} from "@angular/common";
import {HTTP_INTERCEPTORS} from "@angular/common/http";
import {DeepDiveInterceptor} from "./services/deep.dive.interceptor";







//an array of the components that will be passed off to the module
export const allAppComponents = [ CreateTweetComponent, HomeComponent, ListTweetsComponent,MainNavComponent, ProfileComponent, SignInComponent, SignUpComponent];

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