export class Profile {
	constructor(
		public profileId: string,
		public profileAtHandle: string,
		public profileEmail: string,
		public profilePassword: string,
		public profilePasswordConfirm: string
	) {}
}