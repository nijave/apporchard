<div class="row">
    <div id="Decline">
        <?php
		Database::setModerationState("DELETED");
		save();
		?>
    </div>
</div>