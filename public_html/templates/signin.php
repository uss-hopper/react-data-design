<!-- setting up the modal -->
<div class="modal fade" id="signin-modal" tabindex="-1" role="dialog"  aria-labelledby="signin">

	<div class="modal-dialog " role="document">

		<div class="modal-content">

			<div class="modal-header">
				<!-- button to dismiss the modal -->
				<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true"> &times; </span></button>

				<!-- header for the modal -->
				<h4 class="modal-title">Please Sing In</h4>
			</div>


			<div class="modal-body">
				<!-- actual login form -->
				<form #signInForm="ngForm" name="signInForm" id="signInForm" (ngSubmit)="signIn();">
					<!-- username goes here -->
					<div class="form-group">
						<label for="signin-email" class="modal-labels">Email</label>
						<input type="email" name="signin-email" id="signin-email" required [(ngModel)]=signin.profileEmail
								 #profileEmail="ngModel" class="modal-inputs">
					</div>

					<!-- password goes here -->
					<div class="form-group">
						<label for="signin-password" class="modal-labels">Password</label>
						<input type="password" id="signin-password" name="signin-password" required
								 [(ngModel)]="signin.profilePassword" #profilePassword="ngModel" class="modal-inputs">
					</div>

					<div class="form-group" id="signin-final-formgroup">
						<button type="submit" id="submit" [disabled]="signInForm.invalid" class="modal-submit">submit</button>
					</div>
				</form>
			</div>


		</div>
	</div>


</div>