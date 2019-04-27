import React, { Fragment, useState, useEffect } from 'react';
import Button from "react-bootstrap/Button";
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {httpConfig} from "../http/http-config";

import Form from "react-bootstrap/Form";

const Home = () => {

	const [data, setData] = useState({});

	useEffect(() => {
		const fetchTweets = async () => {
			const result = await httpConfig.get("/apis/tweet/");
			setData(result);
		};
		fetchTweets();

	}, []);

	console.log(data);

	return (
		<div className="container">
			<div className="row">
				<div className="col-sm-4">
					<Form>
						<Form.Group controlId="formBasicEmail">
							<Form.Label>Email address</Form.Label>
							<Form.Control type="email" placeholder="Enter email"/>
							<Form.Text className="text-muted">
							</Form.Text>
						</Form.Group>

						<Form.Group controlId="formBasicPassword">
							<Form.Label>Password</Form.Label>
							<Form.Control type="password" placeholder="Password"/>
						</Form.Group>
						<Form.Group controlId="formBasicChecbox">
							<Form.Check type="checkbox" label="Check me out"/>
						</Form.Group>
						<Button variant="primary" type="submit">
							Submit
						</Button>
					</Form>
				</div>
				<div className="col-sm-8">

				</div>


			</div>
		</div>
	)
};


export default Home;
