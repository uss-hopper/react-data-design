import {SignIn} from "./sign-in/SignIn";
import {httpConfig} from "../../shared/http/http-config";

import React, {useState, useEffect} from 'react';


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




