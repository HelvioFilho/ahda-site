<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Email A hora do Anjo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin: 0; padding: 0;">
 <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
 <tr>
  <td align="center" bgcolor="#095D9E" style="padding: 40px 0 30px 0;">
  <a href="<?=base_url(); ?>" target="_blank">
    <img src="cid:<?=$logo?>" alt="A Hora do Anjo" width="auto" height="150" style="display: block;" />
 </a>
</td>
 </tr>
 <tr>
  <td bgcolor="#BEB8B8" style="padding: 40px 30px 40px 30px;">
 <table bgcolor="#fff" style="padding: 15px" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
  <td style="font-size: 1rem">
   Olá, <b><?=$nome?></b>.
  </td>
 </tr>
 <tr>
  <td style=" font-size: 1rem; padding: 20px 0 10px 0;">
   Não lembra sua senha? Para redefinir a senha basta clicar no botão abaixo:<br>
  </td>
 </tr>
 <tr>
  <td>
    <a style="display: block;
      margin: 1rem auto;
      text-align: center;
      font-family: Arial;
      font-weight: 400;
      border: 1px solid transparent;
      width: 8rem;
      padding: 0.375rem 0.75rem;
      font-size: 1rem;
      line-height: 1.5;
      border-radius: 0.25rem;
      cursor: pointer;
      text-decoration: none;
      color: #fff;
      background-color: #36A8FF;
      border-color: #1B92EB;" href="<?=$link?>" target="_blank">Redefinir Senha</a>
      </td>
 </tr>
 <tr>
  <td>
    <p style="font-size: 1rem;">Caso não esteja conseguindo usar o botão, copie e cole o link abaixo em outra janela do seu navegador:</p>
    <span><input type="text" style="color: black;
      text-decoration: none;
      text-align: center;
      width: 95%;" value="<?=$link?>"/></span>
    
  </td>
 </tr>
 <tr>
  <td style="font-size: .9rem;padding: 15px 0 10px 0;">
    <p>Se a mudança não foi solicitada, apenas ignore esse e-mail.</p>
  </td>
 </tr>
 </table>
</td>
 </tr>
 <tr>
 <td style="padding: 30px 30px 30px 30px;">
 <table bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td align="center">
 &copy; <?=date('Y'); ?> A Hora do Anjo. <br>Todos os direitos reservados.<br/>
 
</td>
 </tr>
</table>
</td>
 </tr>
</table>
</body>
</html>