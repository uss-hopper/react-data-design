import {combineReducers} from "redux"
import tweetReducer from "./tweetReducer";

export const combinedReducers = combineReducers({
	tweets: tweetReducer,
});