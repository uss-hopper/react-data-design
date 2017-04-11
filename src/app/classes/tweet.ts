export class Tweet {
	constructor(
		public id: number,
		public tweetProfileId: number,
		public tweetContent: string,
		public tweetDate: string
	) {}
}