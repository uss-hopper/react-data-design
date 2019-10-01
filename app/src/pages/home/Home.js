import React, {useEffect} from 'react';
import {useSelector, useDispatch} from "react-redux";
import {getAllTweets} from "../../shared/actions/tweet";

export const Home = () => {

	const tweets = useSelector(state => state.tweets);
	const dispatch = useDispatch();

	const effects = () => {
		dispatch(getAllTweets());
	};

	const inputs = undefined;

	useEffect(effects,undefined);

	return (
		<>
			<h3>hello world</h3>
		</>


	)
};


