import React, {Component} from 'react'
import Card from "react-bootstrap/Card";
import {Button} from "react-bootstrap/Button";

class Home extends Component {
	render() {
		return (
			<>
				<Card>
					<Card.Body>
						<Card.Title>Card Title</Card.Title>
						<Card.Text>
							Some quick example text to build on the card title and make up the bulk of
							the card's content.
						</Card.Text>
						<Button variant="primary">Go somewhere</Button>
					</Card.Body>
				</Card>
			</>
		);
	}
}

export default Home;
