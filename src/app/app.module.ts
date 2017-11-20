import {NgModule} from "@angular/core";
import {BrowserModule} from "@angular/platform-browser";
import {FormsModule} from "@angular/forms";
import {HttpClientModule} from "@angular/common/http";
import {AppComponent} from "./app.component";
import {allAppComponents, appRoutingProviders, routing} from "./app.routes";
import {JwtModule} from "@auth0/angular-jwt";
import {SessionService} from "./services/session.service";
import {Status} from "./classes/status";


const moduleDeclarations = [AppComponent];

//configure the parameters fot the JwtModule
const JwtHelper = JwtModule.forRoot({
	config: {
		tokenGetter: () => {
			return localStorage.getItem("jwt-token");
		},
		skipWhenExpired:true,
		whitelistedDomains: ["localhost:7272", "https://bootcamp-coders.cnm.edu/"]
	}
});

@NgModule({
	imports:      [BrowserModule, HttpClientModule, JwtHelper, FormsModule, routing],
	declarations: [...moduleDeclarations, ...allAppComponents],
	bootstrap:    [AppComponent],
	providers:    [appRoutingProviders]
})
export class AppModule {

	status : Status = null;

	constructor(private sessionService : SessionService ) {
		this.sessionService.setSession()
			.subscribe(status => this.status = status);
	}
}