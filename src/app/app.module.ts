import {NgModule} from "@angular/core";
import {BrowserModule} from "@angular/platform-browser";
import {FormsModule} from "@angular/forms";
import {HttpModule} from "@angular/http";
import {AppComponent} from "./app.component";
import {allAppComponents, appRoutingProviders, routing} from "./app.routes";
import {SignInService} from "./services/sign-in.service";
import {LikeService} from "./services/like.service";
import {ProfileService} from "./services/profile.service";
import {SignOutService} from "./services/sign-out.service";
import {SignUpService} from "./services/sign-up.service";
import {TweetService} from "./services/tweet.service";

const moduleDeclarations = [AppComponent];

@NgModule({
	imports:      [BrowserModule, FormsModule, HttpModule, routing],
	declarations: [...moduleDeclarations, ...allAppComponents],
	bootstrap:    [AppComponent],
	providers:    [appRoutingProviders, LikeService, ProfileService, SignInService, SignOutService, SignUpService, TweetService]
})
export class AppModule {}