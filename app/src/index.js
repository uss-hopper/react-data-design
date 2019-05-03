import {Home} from "./pages/home/Home";
import {Profile} from "./pages/profile/Profile";
import {Image} from "./pages/image/Image"
import {FourOhFour} from "./pages/four-oh-four/FourOhFour";
import {MainNav} from "./shared/components/MainNav";

import React from "react";
import ReactDOM from "react-dom";
import 'bootstrap/dist/css/bootstrap.css';
import {Route, BrowserRouter, Switch} from 'react-router-dom'

import { library } from '@fortawesome/fontawesome-svg-core'
import { faStroopwafel } from '@fortawesome/free-solid-svg-icons'
import {Provider} from "react-redux";
import {applyMiddleware, createStore} from "redux";
import thunk from "redux-thunk";
import reducers from "./shared/reducers";

const store = createStore(reducers,applyMiddleware(thunk));

library.add(faStroopwafel);



const Routing = (store) => (
	<>
		<Provider store={store}>
		<BrowserRouter>
			<MainNav/>
			<Switch>
				<Route exact path="/" component={Home}/>
				<Route exact path="/profile" component={Profile}/>
				<Route exact path="/image" component={Image}/>
				<Route component={FourOhFour}/>
			</Switch>
		</BrowserRouter>
		</Provider>
	</>
);


ReactDOM.render(<Routing store={store}/> , document.querySelector("#root"));

