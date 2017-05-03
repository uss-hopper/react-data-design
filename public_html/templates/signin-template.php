<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#signin-modal"> Sing In</button>

<div class="modal fade" tabindex="-1" role="dialog" id="signin-modal">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Enter your email and password</h4>
			</div>

			<!-- form and input for login -->
			<form #signInForm="ngForm" name="signInForm" id="signInForm" (ngSubmit)="signIn();">
				<!-- profile email-->
				<div class="form-group">
					<label for="signin-email" class="modal-labels">Email:</label>
					<input type="email" name="signin-email" id="signin-email" required [(ngModel)]="signin.profileEmail" #profileEmail="ngModel" class="modal-inputs">
				</div>

				<!-- profile password -->
				<div class="form-group">
					<label for="signin-password" class="modal-labels">Password</label>
					<input type="password" name="signin-password" id="signin-password" required [(ngModel)]="signin.profilePassword" #profilePassword="ngModel" class="modal-inputs">
			</form>

		</div>
	</div>
</div>