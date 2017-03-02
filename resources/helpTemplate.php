Usage: <?= $scriptName; ?><?= $hasCommands ? ' <command>' : ($command ? ' ' . $command : '') ?><?= $hasOptions ? ' [options]' : '' ?> [operands]
<?php if ($description) : ?>
<?= $description ?>

<?php endif; ?><?= $options; ?><?= $commands ?>
