import React, {useState, useEffect} from 'react';
import Button from "react-bootstrap/Button";
import {httpConfig} from "../../shared/http/http-config";
import Form from "react-bootstrap/Form";
import {Formik} from "formik";
import * as Yup from "yup";

;

export const Home = () => {

	const [data, setData] = useState({});

	const [defaultFormValues, setDefaultFormValues] = useState({
		email: "",
		password: ""
	});

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
			.required('email is required'),
		password: Yup.string()
			.required("Password is required")
			.min(8, "Password must be at least eight characters")
	});

	return (
		<>
			<div className="container">
				<div className="row">
					<div className="col-sm-4">

						<Formik
							initialValues={defaultFormValues}
							onSubmit={(values, {setStatus, status}) => {
								httpConfig.post("/apis/sign-in/", values)
									.then(reply => setStatus(reply));
								console.log(status);
							}}
							validationSchema={validator}
						>
							{props => {
								const {
									status,
									values,
									errors,
									touched,
									dirty,
									isSubmitting,
									handleChange,
									handleBlur,
									handleSubmit,
									handleReset
								} = props;
								return (
									<>
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
												{
													errors.email && touched.email && (
														<div className="alert alert-danger">
															{errors.email}
														</div>)

												}
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
												{errors.password && touched.password && (
													<div className="alert alert-danger">{errors.password}</div>
												)}
											</Form.Group>

											<Form.Group>
												<Button variant="primary" type="submit">Submit</Button>
												<Button
													variant="primary"
													onClick={handleReset}
													disabled={!dirty || isSubmitting}
												>Reset</Button>
											</Form.Group>
											<DisplayFormikState {...props} />
										</Form>
										{status && (<div className={status.type}>{status.message}</div>)}
									</>
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
	<div style={{margin: '1rem 0'}}>
		<h3 style={{fontFamily: 'monospace'}}/>
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

