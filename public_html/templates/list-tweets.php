<div class="col-xs-10 col-xs-offset-1 well">
	<h3>All The Things</h3>

	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>User</th>
				<th>Tweet</th>
				<th>Date</th>
			</tr>
		</thead>
		<tbody>
			<tr #ngFor="let tweet of tweets">
				<td>Bob Dole</td>
				<td> {{tweet.tweetContent }}</td>
				<td> {{ tweet.tweetDate | date: 'medium'}} </td>
				<td></td>
			</tr>
		</tbody>
	</table>


</div>