import Home from "./views/Home";
import Profile from "./views/Profile";
import Image from "./views/Image"
import FourOhFour from "./views/FourOhFour";


import React from "react";
import ReactDOM from "react-dom";
import 'bootstrap/dist/css/bootstrap.css';
import {Route, BrowserRouter, Switch} from 'react-router-dom'
import Navbar from "react-bootstrap/Navbar";
import Nav from "react-bootstrap/Nav";
import Form from "react-bootstrap/Form";
import {FormControl} from "react-bootstrap";
import Button from "react-bootstrap/Button";
import {LinkContainer} from "react-router-bootstrap";
import { library } from '@fortawesome/fontawesome-svg-core'
import { faStroopwafel } from '@fortawesome/free-solid-svg-icons'


library.add(faStroopwafel);





const checkActive = (match, location) => {
	if(!match) return false;
	//some additional logic to verify you are in the home URI
	if(!location) return false;
	const {path} = match;
	const {pathname} = location;
	return (pathname !== path)
};



const routing = (
	<>
		<BrowserRouter>
			<Navbar bg="primary" variant="dark">
				<LinkContainer exact to="/" isActive={checkActive}>
					<Navbar.Brand>Navbar</Navbar.Brand>
				</LinkContainer>
				<Nav className="mr-auto">
					<LinkContainer exact to="/profile"
										isActive={checkActive}
										activeStyle={{
											fontWeight: "bold",
											color: "red"
										}}
					><Nav.Link>profile</Nav.Link>
					</LinkContainer>
					<LinkContainer exact to="/image" isActive={checkActive}
					><Nav.Link>Pricing</Nav.Link>
					</LinkContainer>
				</Nav>
				<Form inline>
					<FormControl type="text" placeholder="Search" className="mr-sm-2"/>
					<Button variant="outline-light">Search</Button>
				</Form>
			</Navbar>
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

