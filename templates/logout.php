<div class="row">
	<div id="login-form">
		<h2>Successfully logged out.</h2>
		<?php
			$user->logout();
		?>
	</div>
        <script type="text/javascript">
            setTimeout(function () {
                window.location.href = "/";
            }, 2000);
        </script>
</div>