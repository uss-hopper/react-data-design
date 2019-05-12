import {Formik} from "formik";
import React from "react";

export const FormikWrapper = ({
								  initialValues,
								  validators,
								  formContent,
								  submitFunction
							  }) => {
	return (
		<Formik
			initialValues={initialValues}
			onSubmit={submitFunction}
			validationSchema={validators}
		>
			{formContent}
		</Formik>
	)
};

