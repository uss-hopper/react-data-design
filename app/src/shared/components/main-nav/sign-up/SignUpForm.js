import React, {useState} from 'react';
import {httpConfig} from "../../../http/http-config";
import {Formik} from "formik/dist/index";
import * as Yup from "yup";
import {SignInFormContent} from "../sign-in/SignInFormContent";

export const SignUpForm = () => {

	[status, setStatus] = useState(null);

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

	return (
		<>
			<Formik
				initialValues={signUp}
				onSubmit={submitSignUp}
				validationSchema={validator}
			>
				{SignInFormContent}
			</Formik>
		</>
	)
};