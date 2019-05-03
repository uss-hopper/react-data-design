import {SignIn} from "./sign-in/SignIn";
import {httpConfig} from "../../shared/http/http-config";

import React, {useState, useEffect} from 'react';
import {connect} from "react-redux";
import {getTweets} from "../../shared/actions/tweet";


const HomeComponent = ({getTweets, tweets}) => {

	useEffect(() => {
		getTweets()
	}, []);

	console.log(tweets);


	return (
		<>

		<SignIn/>
		</>
	)
};

const mapStateToProps = ({tweets}) => {
	return {tweets};

};

export const Home = connect(mapStateToProps, {getTweets}
)(HomeComponent);



