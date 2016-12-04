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
    <?php if ($sessionManager->isAlive()): ?>
      <button type="submit" class="ui button red" id="game-cancel" name="reset" value="true">Spiel abbrechen!</button>
    <?php else: ?>
      <button type="submit" class="ui button red" id="game-cancel" name="reset" value="true">Spiel neustarten!</button>
      <button type="submit" class="ui button red" id="game-cancel-in-ressort" name="reset-in-ressort" value="true">Spiel im Ressort neustarten!</button>
    <?php endif; ?>
  </form>
</div>
