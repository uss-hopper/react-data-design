//import needed @angularDependencies
import {RouterModule, Routes} from "@angular/router";

//import all needed components
import {CreateTweetComponent} from "./components/create.tweet.component";
import {HomeComponent} from "./components/home.component";
import {ListTweetsComponent} from "./components/list.tweets.component";
import {MainNavComponent} from "./components/main.nav";
import {SignInComponent} from "./components/signin.component";
import {SignUpComponent} from "./components/sign.up.component";

// import all needed Services

import {CookieService} from "ng2-cookies";
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
export const allAppComponents = [ CreateTweetComponent, HomeComponent, ListTweetsComponent,MainNavComponent, SignInComponent, SignUpComponent];

//an array of routes that will be passed of to the module
export const routes: Routes = [
	{path: "", component: HomeComponent}
];

// an array of services
const services : any[] = [CookieService,LikeService, ProfileService, SessionService, SignInService,  SignUpService, TweetService];

// an array of misc providers
const providers : any[] = [
	{provide: APP_BASE_HREF, useValue: window["_base_href"]},
	{provide: HTTP_INTERCEPTORS, useClass: DeepDiveInterceptor, multi: true},

];

export const appRoutingProviders: any[] = [providers, services];

export const routing = RouterModule.forRoot(routes);