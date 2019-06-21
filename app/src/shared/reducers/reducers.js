import {combineReducers} from "redux"
import tweetReducer from "./tweetReducer";

export const combinedreducers = combineReducers({
	tweets: tweetReducer,
});