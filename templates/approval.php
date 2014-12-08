<div class="row">
	<div id="Approval Form">
		<h2>Admin New App Approval</h2>
		<?php
                if ($user->isSigned()) {
                    if($user->GroupID < $user::MODERATOR){
                        echo "You do not have access to this page";
                    }
                    else{
                        $apps = Database::applicationGetPending();

                        for($i = 0; $i < count($apps); $i++):
                            $a = new Application($apps[$i]);
                            echo'<form action="/" method="post"><h3>'; 
                            echo '<a href="/?page=details&id='.$a->getID().'">'.$a->getTitle().'</a>'; 
                            echo '</h3><p>'; 
                            echo $a->getDeveloper();
                            echo '</p><p>';
                            echo $a->getDescription(); 
                            echo '</p>
                                <input type="hidden" name="id" value="'.$a->getID().'">
                                <input type="submit" class="btn btn-default" name="action" value="Accept">
                                <input type="submit" class="btn btn-default" name="action" value="Decline">
                            </form>';
                        endfor;			
                    }
                }
                else {
                    echo 'You must be logged in to view this page';
                }
		?>	
	</div>
</div>