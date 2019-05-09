import React from 'react';
import {httpConfig} from "../../http/http-config";
import {Formik} from "formik/dist/index";
import * as Yup from "yup";


export const SignIn = () => {
	const validator = Yup.object().shape({
		profileEmail: Yup.string()
			.email("email must be a valid email")
			.required('email is required'),
		profilePassword: Yup.string()
			.required("Password is required")
			.min(8, "Password must be at least eight characters")
	});


	//the initial values object defines what the request payload is.
	const signIn = {
		profileEmail:"",
		profilePassword:""
	};

	const submitSignIn = (values, {resetForm, setStatus}) => {
		httpConfig.post("/apis/sign-in/", values)
			.then(reply => {
				let {message, type} = reply;
				setStatus({message, type});
				if(reply.status === 200 && reply.headers["x-jwt-token"]) {
					window.localStorage.removeItem("jwt-token");
					window.localStorage.setItem("jwt-token", reply.headers["x-jwt-token"]);
					resetForm();
				}
			});
	};

	return (
		<>
			<div className="container">
				<div className="row">
					<div className="col-sm-4">

						<Formik
							initialValues={signIn}
							onSubmit= {submitSignIn}
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
										{/*controlId must match what is passed to the initialValues prop*/}
											<Form.Group controlId="profileEmail">
												<Form.Label>Email Address</Form.Label>
												<Form.Control
													type="email"
													value={values.profileEmail}
													placeholder="Enter email"
													onChange={handleChange}
													onBlur={handleBlur}

												/>
												{
													errors.profileEmail && touched.profileEmail && (
														<div className="alert alert-danger">
															{errors.profileEmail}
														</div>)

												}
											</Form.Group>
											{/*controlId must match what is defined by the initialValues object*/}
											<Form.Group controlId="profilePassword">
												<Form.Label>Password</Form.Label>
												<Form.Control
													type="password"
													placeholder="Password"
													value={values.profilePassword}
													onChange={handleChange}
													onBlur={handleBlur}
												/>
												{ errors.profilePassword && touched.profilePassword && (
													<div className="alert alert-danger">{errors.profilePassword}</div>
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
											<FormDebugger {...props} />
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



