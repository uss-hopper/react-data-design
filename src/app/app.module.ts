import {NgModule} from "@angular/core";
import {BrowserModule} from "@angular/platform-browser";
import {FormsModule} from "@angular/forms";
import {HttpClientModule} from "@angular/common/http";
import {AppComponent} from "./app.component";
import {allAppComponents, appRoutingProviders, routing} from "./app.routes";
import {SessionService} from "./services/session.service";
import {JwtModule} from "@auth0/angular-jwt";


const moduleDeclarations = [AppComponent];

//configure the parameters fot the JwtModule
const JwtHelper = JwtModule.forRoot({
	config: {
		tokenGetter: () => {
			return localStorage.getItem("access_token");
		},

		skipWhenExpired:true,

		whitelistedDomains: ["localhost:7878", "https://bootcamp-coders.cnm.edu/"]
	}
});

@NgModule({
	imports:      [BrowserModule, HttpClientModule, JwtHelper, FormsModule, routing],
	declarations: [...moduleDeclarations, ...allAppComponents],
	bootstrap:    [AppComponent],
	providers:    [appRoutingProviders]
})
export class AppModule {
	constructor(protected sessionService: SessionService) {


		this.run();
	}

	run() : void {
		this.sessionService.setSession();

	}
}