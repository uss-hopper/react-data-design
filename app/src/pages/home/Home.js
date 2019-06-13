import React, {useEffect} from 'react';
import {useSelector, useDispatch} from "react-redux";
import {getAllTweets} from "../../shared/actions/tweet";

export const Home = () => {

	const tweets = useSelector(state => state.tweets);
	const dispatch = useDispatch();
	const test = "testing123";
	console.log(tweets);


	useEffect((test) => {
		dispatch(getAllTweets(test));
	}, [test]);

	return (
		<>
			<h3>hello world</h3>
		</>


	)
};



