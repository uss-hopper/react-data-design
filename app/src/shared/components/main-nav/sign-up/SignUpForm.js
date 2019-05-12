import React, {useState} from 'react';
import {httpConfig} from "../../../misc/http-config";
import * as Yup from "yup";
import {FormikWrapper} from "../../../misc/FormikWrapper";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {FormDebugger} from "../../FormDebugger";

export const SignUpForm = () => {


	const validator = Yup.object().shape({
		profileEmail: Yup.string()
			.email("email must be a valid email")
			.required('email is required'),
		profileHandle: Yup.string()
			.required("profile handle is required"),
		profilePassword: Yup.string()
			.required("Password is required")
			.min(8, "Password must be at least eight characters"),
		profilePasswordConfirm: Yup.string()
			.required("Password Confirm is required")
			.min(8, "Password must be at least eight characters"),
		profilePhone: Yup.string()
			.min(10, "phone number is to short").max(10, "phone Number is to long")
	});

	const signUp = {
		profileEmail: "",
		profileHandle: "",
		profilePassword: "",
		profilePasswordConfirm: "",
		profilePhone: ""
	};

	const submitSignUp = (values, {resetForm, setStatus}) => {
		httpConfig.post("/apis/sign-up/", values)
			.then(reply => {
					let {message, type} = reply;
					setStatus({message, type});
					if(reply.status === 200) {
						resetForm();
					}
				}
			);
	};

	const formContent = (props) => {
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
							<div className="input-group-prepend">
								<div className="input-group-text">
									<FontAwesomeIcon icon="envelope"/>
								</div>
							</div>
							<input
								className="form-control"
								id="profileEmail"
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
							<div className="input-group-prepend">
								<div className="input-group-text">
									<FontAwesomeIcon icon="key"/>
								</div>
							</div>
							<input
								id="profilePassword"
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
						<label htmlFor="profilePasswordConfirm">Confirm Your Password</label>
						<div className="input-group">
							<div className="input-group-prepend">
								<div className="input-group-text">
									<FontAwesomeIcon icon="key"/>
								</div>
							</div>
							<input
								id="profilePasswordConfirm"
								className="form-control"
								type="password"
								placeholder="Password Confirm"
								value={values.profilePasswordConfirm}
								onChange={handleChange}
								onBlur={handleBlur}
							/>
						</div>
						{errors.profilePasswordConfirm && touched.profilePasswordConfirm && (
							<div className="alert alert-danger">{errors.profilePasswordConfirm}</div>
						)}
					</div>


					<div className="form-group">
						<label htmlFor="profileHandle">@Handle</label>
						<div className="input-group">
							<div className="input-group-prepend">
								<div className="input-group-text">
									<FontAwesomeIcon icon="envelope"/>
								</div>
							</div>
							<input
								className="form-control"
								id="profileHandle"
								type="email"
								value={values.profileHandle}
								placeholder="@Handle"
								onChange={handleChange}
								onBlur={handleBlur}

							/>
						</div>
						{
							errors.profileHandle && touched.profileHandle && (
								<div className="alert alert-danger">
									{errors.profileHandle}
								</div>
							)
						}
					</div>


					<div className="form-group">
						<label htmlFor="profilePhone">Phone Number</label>
						<div className="input-group">
							<div className="input-group-prepend">
								<div className="input-group-text">
									<FontAwesomeIcon icon="envelope"/>
								</div>
							</div>
							<input
								className="form-control"
								id="profilePhone"
								type="text"
								value={values.profilePhone}
								placeholder="Enter email"
								onChange={handleChange}
								onBlur={handleBlur}
							/>
						</div>
						{
							errors.profilePhone && touched.profilePhone && (
								<div className="alert alert-danger">
									{errors.profileEmail}
								</div>
							)

						}
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
	};
	return (
		<>
			<FormikWrapper
				formContent={formContent}
				initialValues={signUp}
				submitFunction={submitSignUp}
				validators={validator}
			/>
		</>
	)
};
