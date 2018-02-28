export class Tweet {
	constructor(
		public tweetId: number,
		public tweetProfileId: number,
		public tweetContent: string,
		public tweetDate: string
	) {}
}