import React from "react";
import ReactDOM from "react-dom";
import 'bootstrap/dist/css/bootstrap.css';

import Home from "./pages/Home";
import { Route, Link, BrowserRouter as Router } from 'react-router-dom'
import Profile from "./pages/Profile";
import Image from "./pages/Image"



const routing = (
	<Router>
		<>
			<Route exact path="/" component={Home} />
			<Route path="/profile" component={Profile} />
			<Route path="/image" component={Image} />
		</>
	</Router>
);



ReactDOM.render(
	routing,
	document.querySelector("#root")
);

