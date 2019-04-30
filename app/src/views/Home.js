import React, {useState, useEffect} from 'react';
import Button from "react-bootstrap/Button";
import {httpConfig} from "../http/http-config";

import Form from "react-bootstrap/Form";
import {Formik} from "formik";
import * as Yup from "yup";

const Home = () => {

	const [data, setData] = useState({});

	useEffect(() => {
		const fetchTweets = async () => {
			const result = await httpConfig.get("/apis/tweet/");
			setData(result);
		};
		fetchTweets();

	}, []);


	const validator = Yup.object().shape({
		email: Yup.string()
			.email()
			.required('Required'),
		password: Yup.string()
			.required("Password Is Required")
	});

	return (
		<>
			<div className="container">
				<div className="row">
					<div className="col-sm-4">

						<Formik
							initialValues={{
								email: "",
								password: ""
							}}
							onSubmit={ (values, {setSubmitting}) => {
								console.log(values);

							}}
							validationSchema={validator}
						>
							{props => {
								const {
									values,
									dirty,
									isSubmitting,
									handleChange,
									handleBlur,
									handleSubmit,
									handleReset
								} = props;
								return (
									<Form onSubmit={handleSubmit}>
										<Form.Group controlId="email">
											<Form.Label>Email address</Form.Label>
											<Form.Control
												type="email"
												value={values.email}
												placeholder="Enter email"
												onChange={handleChange}
												onBlur={handleBlur}

											/>
										</Form.Group>

										<Form.Group controlId="password">
											<Form.Label>Password</Form.Label>
											<Form.Control
												type="password"
												placeholder="Password"
												value={values.password}
												onChange={handleChange}
												onBlur={handleBlur}
											/>
										</Form.Group>

										<Form.Group controlId="formBasicChecbox">
											<Form.Check type="checkbox" label="Check me out"/>
										</Form.Group>
										<Button variant="primary" type="submit">Submit</Button>
										<Button
											variant="primary"
											onClick={handleReset}
											disabled={!dirty || isSubmitting}
										>Reset</Button>
										<DisplayFormikState {...props} />
									</Form>
								)
							}}

						</Formik>
					</div>
					<div className="col-sm-8">
					</div>
				</div>
			</div>
		</>
	)
};

export const DisplayFormikState = props =>
	<div style={{ margin: '1rem 0' }}>
		<h3 style={{ fontFamily: 'monospace' }} />
		<pre
			style={{
				background: '#f6f8fa',
				fontSize: '.65rem',
				padding: '.5rem',
			}}
		>
      <strong>props</strong> ={' '}
			{JSON.stringify(props, null, 2)}
    </pre>
	</div>;


export default Home;
