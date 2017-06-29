export class Profile {
	constructor(
		public profileId: number,
		public profileAtHandle: string,
		public profileEmail: string,
		public profilePassword: string,
		public profilePasswordConfirm: string
	) {}
}