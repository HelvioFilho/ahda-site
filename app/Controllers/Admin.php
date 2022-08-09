<?php

namespace App\Controllers;

use App\Models\UserModel;

class Admin extends BaseController
{
  
  public function verificarUser()
  {
    $useModel = new UserModel();
    $login = $useModel->where('email',strtolower($this->request->getPost('login')))->first();
    if(isset($login)){
      echo 'true';
    }else{
      echo 'false';
    }
  }

  public function logando()
  {
    $useModel = new UserModel();
    $login = $useModel->where('email',strtolower($this->request->getPost('login')))->first();
    if (!isset($login)) {
      echo "login";
    } elseif (!password_verify($this->request->getPost('pss'), $login->passwd)) {
      echo "senha";
    } else {
      $verificar = $useModel->logar($login, $this->request->getPost('box'));
      if ($verificar) {
        echo "logado";
      } else {
        echo "block";
      }
    }
  }

  public function criar_adm()
  {
    $useModel = new UserModel();
    $gera = mt_rand(1, 9999);
    $alt = [
      'user_id' => $gera,
      'acesso' => 1,
      'username' => "helviosvf@gmail.com",
      'email' => "helviosvf@gmail.com",
      'passwd' => password_hash("050687", PASSWORD_DEFAULT),
      'criacao' => date('Y-m-d H:i:s')
    ];

    $inserir = $useModel->inserir($alt);
    if ($inserir) {
      echo "Usuário admin criado com sucesso";
    } else {
      echo "Algo deu errado";
    }
  }

  public function recuperar_email()
  {
    $useModel = new UserModel();
    echo $useModel->recuperarEmail($this->request->getPost('email'));
  }

  public function recuperar_senha($chave = null, $id = null)
  {
    $session = session();
    $useModel = new UserModel();

    $clava = explode("Q1T1Q", $chave);
    $id = explode("Q1T1Q", $id);

    if (isset($id[1])) {
      $infor = $useModel->where('user_id',$id[1])->first();
      if (isset($infor)) {
        if ($clava[0] === url_title($infor->passwd)) {
          return view(
            'recuperar_senha',
            [
              'infor' => $infor,
            ]
          );
        } else {
          $session->setFlashdata('msg', 'Link já utilizado, se ainda precisa recuperar a senha, solicite um novo link apertando em <b>Esqueci a senha</b>!');
          redirect(base_url(), 'refresh');
        }
      } else {
        $session->setFlashdata('msg', 'Usuário do link é inválido, se o problema persistir entre em contato com um administrador!');
        redirect(base_url(), 'refresh');
      }
    } elseif ($chave == 'holdinster') {
      echo $useModel->where('user_id',$this->request->getPost('id'))->set(
        [
          'passwd' => password_hash($this->request->getPost('senha'), PASSWORD_DEFAULT),
          'passwd_modificado' => date('Y-m-d H:i:s'),
        ],
        $this->request->getPost('id')
      )->update();
    } else {
      redirect(base_url(), 'refresh');
    }
  }
}