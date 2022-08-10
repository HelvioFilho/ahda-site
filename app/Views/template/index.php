<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<?= $this->include('template/header'); ?>
</head>
<body>
	<?= $this->include('template/menu'); ?>
	<?= $this->renderSection('body'); ?>
	<?= $this->include('template/footer'); ?>
</body>
</html>