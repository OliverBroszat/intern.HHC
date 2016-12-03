<?php global $sessionManager ?>

<?php getTemplatePart('image') ?>

<div class="ui segment" id="solutions">
 <form action="." method="POST">
  <div class="ui grid">
    <div class="two column row">
      <?php
        foreach ($sessionManager->getChars() as $char) {
          echo '
            <div class="column">
              <button type="submit" class="ui button blue fluid '.$sessionManager->getDisabledClassIfNotAlive().'" name="solution" value="'.$char->id.'" value="">
                '.$char->firstName.' '.$char->lastName.'
              </button>
            </div>
          ';
        }
      ?>
     </div><!-- /.row -->
    </div><!-- /.grid -->
  </form>
</div><!-- /.segment -->