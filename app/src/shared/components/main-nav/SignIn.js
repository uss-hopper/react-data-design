import React from 'react';
import {httpConfig} from "../../http/http-config";
import {Formik} from "formik/dist/index";
import * as Yup from "yup";
import {FormDebugger} from "../FormDebugger";


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
		profileEmail: "",
		profilePassword: ""
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


			<Formik
				initialValues={signIn}
				onSubmit={submitSignIn}
				validationSchema={validator}
			>
				{(props) => {
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
							<form onSubmit={handleSubmit}>
								{/*controlId must match what is passed to the initialValues prop*/}
								<div className="form-group">
									<label htmlFor="profileEmail">Email Address</label>
									<div className="input-group">
										<input
											className="form-control"
											type="email"
											value={values.profileEmail}
											placeholder="Enter email"
											onChange={handleChange}
											onBlur={handleBlur}

										/>
									</div>
									{
										errors.profileEmail && touched.profileEmail && (
											<div className="alert alert-danger">
												{errors.profileEmail}
											</div>
										)

									}
								</div>
								{/*controlId must match what is defined by the initialValues object*/}
								<div className="form-group">
									<label htmlFor="profilePassword">Password</label>
									<div className="input-group">
										<input
											className="form-control"
											type="password"
											placeholder="Password"
											value={values.profilePassword}
											onChange={handleChange}
											onBlur={handleBlur}
										/>
									</div>
									{errors.profilePassword && touched.profilePassword && (
										<div className="alert alert-danger">{errors.profilePassword}</div>
									)}
								</div>

								<div className="form-group">
									<button className="btn btn-primary mb-2" type="submit">Submit</button>
									<button
										className="btn btn-danger mb-2"
										onClick={handleReset}
										disabled={!dirty || isSubmitting}
									>Reset
									</button>
								</div>
								<FormDebugger {...props} />
							</form>
							{status && (<div className={status.type}>{status.message}</div>)}
						</>
					)
				}}
			</Formik>
		</>
	)
};



