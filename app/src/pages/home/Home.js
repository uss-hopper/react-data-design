import React, {useEffect} from 'react';
import {useSelector, useDispatch} from "react-redux";
import {getAllTweets} from "../../shared/actions/tweet";
import Card from "react-bootstrap/Card";

export const Home = () => {

	const tweets = useSelector(state => state.tweets);
	const dispatch = useDispatch();

	const effects = () => {
		dispatch(getAllTweets());
	};

	const inputs = [];

	useEffect(effects,inputs);

	return (
		<>
			{tweets.map(tweet => {
				return(
				<Card style={{ width: '18rem' }} key={tweet.tweetId}>
				<Card.Img variant="top" src={tweet.profileAvatarUrl} />
				<Card.Body>
					<Card.Text>{tweet.profileAtHandle}</Card.Text>
					<Card.Text>{new Date(tweet.tweetDate).toDateString()}</Card.Text>
					<Card.Text>
						{tweet.tweetContent}
					</Card.Text>
				</Card.Body>
			</Card>)
			})}
		</>


	)
};


