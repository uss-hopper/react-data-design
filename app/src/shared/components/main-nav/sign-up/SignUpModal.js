import React from "react";
import {SignUpForm} from "./SignUpForm";

export const SignUpModal = () => (
	<>
		<button type="button" className="btn btn-primary" data-toggle="modal" data-target="#signUpModal">
			Sign Up
		</button>

		<div className="modal fade" id="signUpModal" tabIndex="-1" role="dialog" aria-labelledby="exampleModalLabel"
			  aria-hidden="true">
			<div className="modal-dialog" role="document">
				<div className="modal-content">
					<div className="modal-header">
						<h5 className="modal-title" id="exampleModalLabel">Sign Up</h5>
						<button type="button" className="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div className="modal-body">
						<SignUpForm/>
					</div>
				</div>
			</div>
		</div>
	</>
);
