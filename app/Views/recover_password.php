<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>A Hora do Anjo</title>
	<link rel="shortcut icon" type="image/x-icon" href="<?=base_url(['icone','angel.svg']) ?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url(['assets','css','bootstrap.css']) ?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url(['assets','css','adm-index.css']) ?>">
</head>
<body>
	<main class="form-change">
		<form>
			<div class="text-center">
			    <img class="mb-3 own" src="<?=base_url(['icone','angel.svg']) ?>" alt="" width="72" height="57">
			    <h1 class="h3 mb-3 fw-normal">Alterar senha</h1>
			</div>
			<div id="campo_1" class="form-floating">
				<input id="password" type="password" autocomplete="off" class="form-control" placeholder="senha" >
				<label for="password"> Nova Senha</label>
			</div>
			<input id="hidden" type="hidden" name=id value="<?=$infor->user_id ?>">
			<div id="campo_2" class="form-floating">
				<input id="repassword" type="password" autocomplete="off" class="form-control" placeholder="re-senha">
				<label for="repassword"><span class="erroPss"></span> Repetir a Senha</label>
			</div>
			<div class="alert">	    
			</div>
			<button id="cm-btn" class="w-100 btn btn-lg btn-primary" disabled type="submit">Alterar</button>
			<p class="mt-4 text-muted text-center">&copy; 2022 <br> Desenvolvido por <a class="via text-muted" target="_blank" href="https://hsvf.com.br">hsvf</a></p>
		</form>
	</main>
<script src="<?=base_url(['assets','js','adm-recuperar-senha.js']) ?>" type="text/javascript" charset="utf-8" async defer></script>
<script>
let url = "<?=base_url();?>";
</script>
<script src="<?=base_url(['assets','js','bootstrap.js']) ?>" type="text/javascript" charset="utf-8"></script>