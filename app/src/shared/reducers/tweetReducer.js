export default (state = [], action) => {
	switch(action.type) {
		case "FETCH_TWEETS":
			return action.payload;
		default:
			return state;
	}
}