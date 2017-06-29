import {RouterModule, Routes} from "@angular/router";
import {HomeComponent} from "./components/home-component";
import {MainNavComponent} from "./components/main-nav";
import {SignInComponent} from "./components/signin.component";
import {SignUpComponent} from "./components/sign-up.component";

export const allAppComponents = [HomeComponent, MainNavComponent, SignInComponent, SignUpComponent];

export const routes: Routes = [
	{path: "", component: HomeComponent}
];

export const appRoutingProviders: any[] = [];

export const routing = RouterModule.forRoot(routes);