<?php 
  global $sessionManager;
?>

<div class="ui segment">
  <span>Leben</span>
  <span class="ui red circular label"><?=$_SESSION['lifes']?></span>
  <span>Punkte</span>
  <span class="ui green circular label"><?=$_SESSION['points']?></span>
  <span>Ressort</span>
  <span class="ui blue circular label"><?=$_SESSION['ressort_description']?></span>
</div>

<div class="ui segment">
  <form action="." method="POST">
    <button type="submit" class="ui button red" name="reset" value="true">Spiel <?=($sessionManager->isAlive() ? 'abbrechen!' : 'neustarten!')?></button>
  </form>
</div>