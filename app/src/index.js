import {Home} from "./pages/home/Home";
import {Profile} from "./pages/profile/Profile";
import {Image} from "./pages/image/Image"
import {FourOhFour} from "./pages/four-oh-four/FourOhFour";
import {MainNav} from "./shared/components/MainNav";
import thunk from "redux-thunk";
import reducers from "shared/reducers"
import {applyMiddleware, createStore} from "redux";
import React from "react";
import ReactDOM from "react-dom";
import 'bootstrap/dist/css/bootstrap.css';
import {Route, BrowserRouter, Switch} from 'react-router-dom'

import { library } from '@fortawesome/fontawesome-svg-core'
import { faStroopwafel } from '@fortawesome/free-solid-svg-icons'

const store = createStore(reducers,applyMiddleware(thunk));

library.add(faStroopwafel);



const routing = (
	<>
		<BrowserRouter>
			<MainNav/>
			<Switch>
				<Route exact path="/" component={Home}/>
				<Route exact path="/profile" component={Profile}/>
				<Route exact path="/image" component={Image}/>
				<Route component={FourOhFour}/>
			</Switch>
		</BrowserRouter>
	</>
);


ReactDOM.render(routing, document.querySelector("#root"));

