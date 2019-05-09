export default (state = [], action) => {
	switch(action.type) {
		case "GET_ALL_TWEETS":
			return action.payload;
		default:
			return state;
	}
}