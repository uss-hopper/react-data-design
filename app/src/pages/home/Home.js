import {SignIn} from "./sign-in/SignIn";

import React, {useState, useEffect} from 'react';
import Button from "react-bootstrap/Button";
import {httpConfig} from "../../shared/http/http-config";
import Form from "react-bootstrap/Form";
import {Formik} from "formik";
import * as Yup from "yup";


export const Home = () => {

	const [data, setData] = useState({});

	useEffect(() => {
		const fetchTweets = async () => {
			const result = await httpConfig.get("/apis/tweet/");
			setData(result);
		};
		fetchTweets();

	}, []);

	return (
		<SignIn/>
	)
};




