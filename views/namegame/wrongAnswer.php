<?php global $sessionManager; ?>

<?php getTemplatePart('image') ?>

<div class="ui segment" id="solutions">
  <form action="." method="POST">
    <div class="ui grid">
      <div class="two column row">
        <?php
          foreach ($sessionManager->getChars() as $char) {
            if($char->id === $sessionManager->getSelectedChar()->id) {
              echo '
                <div class="column">
                  <button type="submit" class="ui button green fluid '.$sessionManager->getDisabledClassIfNotAlive().'" name="solution" value="'.$char->id.'" value="">
                    '.$char->firstName.' '.$char->lastName.'
                  </button>
                </div>
              ';
            } else {
              echo '
                <div class="column">
                  <button type="submit" class="ui button red fluid disabled" name="solution" value="'.$char->id.'" value="">
                    '.$char->firstName.' '.$char->lastName.'
                  </button>
                </div>
              ';
            }
          }
        ?>
      </div><!-- /.row -->
    </div><!-- /.grid -->
  </form>
</div><!-- /.segment -->