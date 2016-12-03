<?php global $sessionManager ?>

<div class="img-container ui segment">
  <img 
    class="ui segment" 
    style="margin: 3rem auto; width: 50%; display: block;" 
    src="<?=$sessionManager->getSelectedChar()->imageUrl?>" 
    alt=""
  >
</div>