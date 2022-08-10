<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
  protected $table = 'admuser';
  protected $primaryKey = 'user_id';
  protected $returnType = 'object';
  protected $allowedFields = ['user_id', 'username', 'email', 'access', 'passwd', 'recovered_at', 'passwd_changed_at', 'last_login', 'created_at', 'img', 'about', 'count_post', 'is_disabled', 'is_logged_in'];

  public function getAll($limit = NULL, $offset = NULL)
  {
    $db = db_connect();
    $builder = $db->table($this->table);
    $builder->orderBy('created_at', 'DESC');
    if ($limit)
      $builder->limit($limit, $offset);

    $user = $builder->get();

    return $user->getCustomResultObject('User');
  }

  public function setData($user, $updated = NULL)
  {
    $session = session();

    $updated['last_login'] = date('Y-m-d H:i:s');
    $this->where('email', $user->email)->set($updated)->update();
    $newdata = [
      'usuario_logado' => $user ? $user->username : '',
      'email' => $user ? $user->email : '',
      'tipouser' => $user ? $user->access : '',
      'user_id' => $user ? $user->user_id : '',
      'criacao' => $user ? $user->created_at : '',
    ];
    $session->set($newdata);
    return true;
  }

  public function logged($user, $box = NULL)
  {
    $updated = array();
    if ($user->is_disabled == 0) {
      return false;
    }
    if ($box == "lembrar") {
      helper('cookie');

      $cookieKey = url_title($user->passwd, 'underscore', TRUE);
      set_cookie('is_logged_in', $cookieKey, time() + 60 * 60 * 24 * 90);
      $updated['is_logged_in'] = $cookieKey;
    }
    
    return $this->setData($user, $updated);
  }

  public function logoff($message)
  {
    helper('cookie');
    $session = session();

    set_cookie('is_logged_in', '', time() + 60 * 60 * 24 * 0);

    $newdata = [
      'usuario_logado',
      'email',
      'tipouser',
      'user_id',
      'criacao'
    ];
    $session->remove($newdata);
    $session->setFlashdata('msg', $message);
    header("Location: " . base_url(), true, 302);
  }

  public function sendEmail($login)
  {
    $email = \Config\Services::email();

    $nome = ucfirst($login->username);
    $destinatario =  $login->email;
    $binario = mt_rand(100000, 900000);
    $serial = url_title($login->passwd);

    $config['protocol']  = 'smtp';
    $config['smtp_host'] = 'smtp.hostinger.com.br';
    $config['smtp_user'] = $_ENV['SMTP_USER'];
    $config['smtp_pass'] = $_ENV['SMTP_PASS'];
    $config['smtp_port'] = 587;
    $config['wordwrap'] = TRUE; // define se haverá quebra de palavra no texto
    $config['validate'] = TRUE; // define se haverá validação dos endereços de email
    $config['mailtype'] = 'html';
    $config['charset']   = 'utf-8';

    // adaptação
    $linkRec = base_url(['adm', 'recuperar_senha', $serial . 'Q1T1Q' . $binario, 'aoijibSAD' . $binario . 'Q1T1Q' . $login->user_id]);
    $email->initialize($config);
    $email->setFrom('suporte@hsvf.com.br', 'Suporte Admin'); // Remetente
    $email->setTo($destinatario, $nome); // Destinatário
    $email->setSubject("Pedido de redefinição de senha no ADM"); //esolha do assunto
    $email->attach('./img/anjo-branco.png');
    $logo =  $email->setAttachmentCID('./img/anjo-branco.png');
    $email->setMessage(view(
      'email',
      [
        "nome" => $login->username,
        "link" => $linkRec,
        "logo" => $logo,
      ]
    )); //texto de mensagem
    return $this->email->send();
  }

  public function recuperarEmail($email)
  {

    $login = $this->where('email', $email)->first();
    if (isset($login)) {
      $this->where('email', $email)->set(['recovered_at' => date('Y-m-d H:i:s')])->update();
      if ($this->sendEmail($login)) {
        return "ok";
      }
    }
    return "false";
  }

  public function verifyLogin()
  {
    $session = session();
    helper('cookie');
    $cookie = get_cookie('is_logged_in');
    if (isset($cookie)) {
      $user = $this->where('is_logged_in', $cookie)->first();
      if ($user->is_disabled) {
        if (!isset($_SESSION['usuario_logado'])) {
          $this->setData($user);
        }
      }else{
        $this->logoff("Você foi desativado e perdeu o acesso ao sistema!");
      }
    } elseif(isset($_SESSION['user_id'])){
      $check = $this->where('user_id',$_SESSION['user_id'])->first();
      if(!$check->is_disabled){
        $this->logoff("Você foi desativado e perdeu o acesso ao sistema!");
      }
    }else{
      $session->setFlashdata('msg', 'Você foi deslogado do painel administrativo por inatividade!');
      header("Location: " . base_url(), true, 302);
    }
  }
}
