
import React, {useEffect} from 'react';
import {connect} from "react-redux";
import {getAllTweets} from "../../shared/actions";

const HomeComponent = ({getAllTweets, tweets}) => {

	useEffect(() => {
		getAllTweets()

		},
		[getAllTweets]
	);


	return (
		<>
			<h3>hello world</h3>
		</>
	)
};

const mapStateToProps = ({tweets}) => {
	return {tweets};
};

export const Home = connect(mapStateToProps, {getAllTweets}
)(HomeComponent);



