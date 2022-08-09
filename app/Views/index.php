<?php 
$session = session();
if(isset($_SESSION['usuario_logado'])){
	redirect(base_url(['home']),'refresh');
} 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>A Hora do Anjo</title>
	<link rel="shortcut icon" type="image/x-icon" href="<?=base_url(['icon','angel.svg']) ?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url(['assets','css','bootstrap.css']) ?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url(['assets','css','adm-index.css']) ?>">
</head>
<body>
	<!-- Modal -->
<div class="modal fade show" id="forgot-password" tabindex="-1" aria-labelledby="passwordLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="passwordLabel">Esqueceu sua senha?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Para recupera sua senha coloque o e-mail que foi utilizado no cadastro.</p>
        <input id="rec-pass" type="email" name="email" placeholder="Email">
        <p class="forgot-erro"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
        <button id="b-rec" type="button" disabled class="btn btn-primary">Enviar</button>
      </div>
    </div>
  </div>
</div>
	<main class="form-signin">
		<form>
			<div class="text-center">
			    <img class="mb-3 own" src="<?=base_url(['icon','angel.svg']) ?>" alt="" width="72" height="57">
			    <h1 class="h3 mb-3 fw-normal">A Hora do Anjo</h1>
			</div>
			<div class="form-floating">
				<input id="email" type="email" class="form-control" autocomplete="on" placeholder="nome@dominio.com">
				<label for="email"><span class="erroMail"></span> Email</label>
			</div>
			<div class="form-floating">
				<input id="password" type="password" class="form-control" autocomplete="current-password" placeholder="senha">
				<label for="password"><span class="erroPss"></span> Senha</label>
			</div>
			<div class="checkbox mb-0">
				<label>
					<input class="text-start" id="box" type="checkbox" value="lembrar"> Lembrar-me
				</label>
				<a id="forgot" class="forgot" href="#" data-bs-toggle="modal" data-bs-target="#forgot-password"> Esqueci a senha </a>
			</div>
			<div class="alert">
			    <?php  
			    if($session->getFlashdata('msg')){
			    	echo $session->getFlashdata('msg');	
			    }
			    ?>
			</div>
			<button id="cm-btn" class="w-100 btn btn-lg btn-primary" disabled type="submit">Entrar</button>
			<p class="mt-4 text-muted text-center">&copy; 2021 <br> Desenvolvido por <a class="via text-muted" target="_blank" href="https://hsvf.com.br">hsvf</a></p>
		</form>
	</main>
<script src="<?=base_url(['assets','js','bootstrap.js']) ?>" type="text/javascript" charset="utf-8" async defer></script>
<script>
	let url = "<?=base_url();?>";
</script>
<script src="<?=base_url(['assets','js','adm-logar.js']) ?>" type="text/javascript" charset="utf-8"></script>
</body>
</html>