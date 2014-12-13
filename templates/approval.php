<div class="row">
	<div id="Approval Form">
		<h2>Admin New App Approval</h2>
		<?php
                if ($user->isSigned()) {
                    if($user->GroupID < $user::MODERATOR){ ?>
                        <span class="alert alert-danger">You do not have access to this page</span>
                    <?php }
                    else{
                        $apps = Database::applicationGetPending();
                        for($i = 0; $i < count($apps); $i++) {
                            $a = new Application($apps[$i]); ?>
                            <form action="/" method="post">
                                <h3> 
                                    <a href="/?page=details&id=<?php echo $a->getID(); ?>">
                                        <?php echo $a->getTitle(); ?>
                                    </a>
                                </h3>
                                <p>
                                    <?php echo $a->getDeveloper(); ?>
                                </p>
                                <p>
                                    <?php echo $a->getDescription(); ?>
                                </p>
                                <input type="hidden" name="id" value="'.$a->getID().'">
                                <input type="hidden" name="action" value="Approve">
                                <input type="hidden" name="return" value="'. $uri .'">
                                <input type="submit" class="btn btn-default" name="response" value="Accept">
                                <input type="submit" class="btn btn-default" name="response" value="Decline">
                            </form>
                        <?php }		
                    }
                }
                else { ?>
                        <span class="alert alert-danger">You must be logged in to view this page</span>
                <?php } ?>	
	</div>
</div>