
<h1>Create video to post to facebook</h1>
<form
	action="/api/message/facebook"
	method="post"
	enctype="multipart/form-data">
	<label>Token:
	<input
		type="text"
		name="token"/>
	</label>
	<br/>
	<input
		type="file"
		name="file"/>
	<br/>
	<input
		type="submit"
		value="Convert!"/>
</form>

<h1>Response Data</h1>
<div>
<?php var_dump( $output ); ?>
</div>