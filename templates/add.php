<div class="row">
	<div id="add-form">
		<h2>Add Application</h2>
		<form action="/" method="post">
			<label>Title
			<input type="text" class="form-control" name="title" placeholder="Application Title" autofocus></label>
			<br>
			<label>Developer
			<input type="text" class="form-control" name="developer" placeholder="Developer name"></label>
			<br>
			<label>Price
			<span style="position: relative; top: 27px; left: -34px;">$</span><input type="text" class="form-control" name="price" placeholder="1.99" style="padding-left: 16px;"></label>
			<br>
			<label>Compatible Platforms <br>
			<input type="checkbox" name="compatible" value="Apple"> Apple <br>
			<input type="checkbox" name="compatible" value="Android"> Android <br>
			<input type="checkbox" name="compatible" value="Windows"> Windows</label>
			<br>
			<label>Link to developer app page
			<input type="text" class="form-control" name="developer_link" placeholder="http://example.com/"></label>
			<br>
			<label>Application description
			<textarea class="form-control" name="description"></textarea></label>
			<br>
			<input type="submit" class="btn btn-default" name="action" value="Add Application">
		</form>
	</div>
</div>