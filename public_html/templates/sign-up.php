
<div class="modal fade" tabindex="-1" role="dialog" id="signUp-modal">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Please Enter Profile information</h4>
			</div>

			<!-- actual form -->
			<form #signupForm="ngForm" name="signupForm" (ngSubmit)="createSignUp();">

				<!-- twitter at handle -->
				<div class="form-group">
					<label for="atHandle" class="modal-labels">Twitter @Handle</label>
					<input type="text" id="atHandle" name="atHandle" class="modal-inputs" required [(ngModel)] = signUp.profileAtHandle>
				</div>

				<!-- users profile email-->
				<div class="form-group">
					<label for="email" class="modal-labels" >Email Address </label>
					<input type="email" id="email" name="email" class="modal-inputs" required [(ngModel)] = "signUp.profileEmail" #signUpEmail="ngModel">
				</div>

				<!-- users phone number -->
				<div class="form-group">
					<label for="phoneNumber" class="modal-labels" >EPhone Number</label>
					<input type="text" id="phoneNumber" name="phoneNumber" class="modal-inputs" required [(ngModel)] = "signUp.profilePhone" #signupProfilePhone="ngModel">
				</div>

				<!-- user password -->
				<div class="form-group">
					<label for="password" class="modal-labels">Password</label>
					<input type="password" id="password" name="password" class="modal-inputs" required [(ngModel)] = "signUp.profilePassword" #signUpPassword="ngModel">
				</div>

				<!--confirm password -->
				<div class="form-group">
					<label for="passwordConfirm" class="modal-labels">Password Confirm</label>
					<input type="password" id="passwordConfirm" name="passwordConfirm" class="modal-inputs" required [(ngModel)] = "signUp.profilePasswordConfirm" #signUpPasswordConfirm="ngModel">
				</div>

				<!-- Submit button -->
				<input type="submit" name="signUp" class="modal-inputs" value="signUp">

			</form>
		</div>
	</div>
</div>