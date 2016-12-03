<?php
  $ressorts = (new Ressorts())->ressorts;
?>
<form class="ui segment form" action="." method="POST">
  <div class="ui field">
      <label>Wähle das Ressort!</label>
      <div class="ui selection dropdown">
          <input type="hidden" name="ressort" required>
          <i class="dropdown icon"></i>
          <div class="default text">Ressort</div>
          <div class="menu">
            <!-- Print all ressorts as dropdown -->
            <?php
              echo '<div class="item" data-value="'.$allRessortName.'">Alle Ressorts</div>';
              foreach ($ressorts as $ressort) {
                echo '<div class="item" data-value="'.$ressort->name.'">'.$ressort->description.'</div>';
              }
            ?>
          </div>
      </div>
  </div>
  <button type="submit" class="ui button green" id="game-start">Starten!</button>
</form>
