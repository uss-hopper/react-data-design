import {RouterModule, Routes} from "@angular/router";
import {HomeComponent} from "./components/home-component";

export const allAppComponents = [HomeComponent];

export const routes: Routes = [
	{path: "", component: HomeComponent}
];

export const appRoutingProviders: any[] = [];

export const routing = RouterModule.forRoot(routes);