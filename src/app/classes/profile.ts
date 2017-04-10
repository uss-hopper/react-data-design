export class Profile {
	constructor(
		public id: number,
		public profileActivationToken: string,
		public profileAtHandle: string,
		public profileEmail: string,
		public profilePassword: string,
		public profilePasswordConfirm: string
	) {}
}