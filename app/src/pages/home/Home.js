import {SignIn} from "./sign-in/SignIn";
import React, {useEffect} from 'react';
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



