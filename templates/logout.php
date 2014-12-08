<div class="row">
	<div id="login-form">
		<h2>Successfully logged out.</h2>
		<?php
                    $user->logout();

                    $return_uri = isset($_POST['return_uri']) ? filter_input(INPUT_POST, 'return_uri', FILTER_SANITIZE_STRING) : -1;
                    if($return_uri !== -1) {
                        header("Location: " . $return_uri);
                    }
		?>
	</div>
</div>