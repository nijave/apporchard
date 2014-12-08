<div class="row">
	<div id="login-form">
		<h2>Successfully logged out.</h2>
		<?php
                    $user->logout();

                    header("Location: " . $_uri);
		?>
	</div>
</div>