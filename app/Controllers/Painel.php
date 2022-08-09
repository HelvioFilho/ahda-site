<?php

namespace App\Controllers;

use App\Models\UserModel;

class Painel extends BaseController
{
  public function __construct()
  {
    $check = new UserModel();
    // $check->
  }

  public function home()
  {
    $countMsg = $this->msg->countNew();
    $posts = $this->post->getAll(5, 0);
    $mensagens = $this->msg->getAll(5, 0);
    $user = $this->user->getAll();
    $link = $this->post->getRadio();
    $this->load->view(
      'only_page',
      [
        "call" => "adm/index",
        "countMsg" => $countMsg,
        "posts" => $posts,
        "user" => $user,
        "mensagens" => $mensagens,
        "link" => $link,
      ]
    );
  }

  public function myAccount()
  {
    $countMsg = $this->msg->countNew();
    $data = $this->user->get($_SESSION['user_id']);

    $this->load->view(
      'only_page',
      [
        "call" => "adm/minha_conta",
        "data" => $data,
        "countMsg" => $countMsg,
      ]
    );
  }

  public function conta_atualizar_contato()
  {

    $total = [
      'name'   => $this->input->post('name'),
      'email' => $this->input->post('email'),
      'sobre'  => $this->input->post('sobre'),
      'erro'   => false,
      'msg'  => '',
    ];
    $verifica = $this->user->verificaLogin(strtolower($this->input->post('email')));
    if ($verifica != "true" || $_SESSION['email'] == $this->input->post('email')) {
      $update = $this->user->update(
        [
          'username'   => $this->input->post('name'),
          'email'   => $this->input->post('email'),
          'sobre'    => $this->input->post('sobre'),
        ],
        $_SESSION['email']
      );

      $this->session->set_userdata('usuario_logado', $this->input->post('name'));
      $this->session->set_userdata('email', $this->input->post('email'));
    } else {
      $total['erro'] = true;
      $total['msg'] = '* Email já existe!';
    }


    echo json_encode($total);
  }

  public function atualizar_img()
  {
    if (!empty($this->input->post('img')))
      unlink("img/user/" . $this->input->post('img'));

    $url = $this->user->uploadImg('arquivo', $this->input->post('id'));

    if ($url) {
      $update = $this->user->update(
        [
          "img" => $url
        ],
        $_SESSION['email']
      );
      if ($update) {
        $this->session->set_flashdata('error', 'success');
      } else {
        $this->session->set_flashdata('error', 'Erro ao atualizar a imagem.');
      }
    } else {
      $this->session->set_flashdata('error', 'Erro ao salvar a imagem');
    }
    redirect(base_url(['minha_conta']), 'refresh');
  }

  public function usuarios($page = 0)
  {
    $countMsg = $this->msg->countNew();
    $user = $this->user->getAll();
    $this->load->view(
      'only_page',
      [
        "call" => "adm/usuarios",
        "users" => $user,
        "countMsg" => $countMsg,
      ]
    );
  }

  public function add_user()
  {
    $gera = mt_rand(1, 9999);
    $data = [
      'user_id' => $gera,
      'acesso' => $this->input->post('select'),
      'username' => $this->input->post('name'),
      'email' => $this->input->post('email'),
      'passwd' => password_hash("BraVe123#", PASSWORD_DEFAULT),
      'criacao' => date('Y-m-d H:i:s'),
    ];

    $inserir = $this->user->inserir($data);
    if ($inserir) {
      $this->session->set_flashdata('error', 'success');
      $this->session->set_flashdata('msg', 'Usuário adicionado com sucesso!');
    } else {
      $this->session->set_flashdata('error', 'danger');
      $this->session->set_flashdata('msg', 'Não foi possível adicionar o usuário!');
    }
    redirect(base_url(['usuarios']), 'refresh');
  }

  public function del_user($del)
  {
    $dados = $this->user->get($del);
    if ($dados->img) {
      unlink("img/user/" . $dados->img);
    }
    echo $this->user->delete($del);
  }

  public function update_acesso()
  {
    if ($this->input->post('acesso') == 2) {
      $data['acesso'] = 2;
      $data['padrao'] = "<br>Agora é um <b>moderador</b>, podendo criar e excluir usuários, fazer publicações e publicar no aplicativo!";
    } else {
      $data['acesso'] = 3;
      $data['padrao'] = "<br>Agora é um <b>usuário normal</b>, podendo fazer apenas publicações no aplicativo!";
    }
    $update = $this->user->userUpdate(
      [
        'acesso' => $data['acesso'],
      ],
      $this->input->post('id')
    );
    if ($update) {
      $data['error'] = true;
    } else {
      $data['error'] = false;
    }

    echo json_encode($data);
  }

  public function desativar()
  {
    if ($this->input->post('des') == 0) {
      $data['des'] = 1;
      $data['msg'] = "Desativar";
      $data['padrao'] = "ativado";
    } else {
      $data['des'] = 0;
      $data['msg'] = "Ativar";
      $data['padrao'] = "desativado";
    }
    $update = $this->user->userUpdate(
      [
        'desativar' => $data['des'],
      ],
      $this->input->post('id')
    );
    if ($update) {
      $data['error'] = true;
    } else {
      $data['error'] = false;
    }

    echo json_encode($data);
  }

  public function mensagens($page = 0)
  {
    $countMsg = $this->msg->countNew();
    $this->load->library(['pagination']);

    $config = array(
      "base_url"       => base_url(['mensagens']),
      "per_page"       => 5,
      "num_links"     => 2,
      "use_page_numbers"   => TRUE,
      "uri_segment"     => 2,
      "total_rows"     => $this->msg->countAll(),
    );
    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    $config['attributes'] = ['class' => 'page-link'];
    $config['first_link'] = false;
    $config['last_link'] = false;
    $config['first_tag_open'] = '<li class="page-item">';
    $config['first_tag_close'] = '</li>';
    $config['prev_link'] = '&laquo';
    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';
    $config['next_link'] = '&raquo';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li class="page-item">';
    $config['last_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
    $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';

    if ($page !== 0) {
      $cont = $page - 1;
      $limite = $cont * 5;
    } else {
      $limite = 0;
    }

    $mensagens = $this->msg->getAll($config['per_page'], $limite);

    if ($page != 0) {
      if (empty($mensagens)) {
        redirect(base_url(['mensagens']), 'refresh');
      }
    }

    $this->pagination->initialize($config);

    $this->load->view(
      'only_page',
      [
        "call" => "adm/mensagens",
        "mensagens" => $mensagens,
        "countMsg" => $countMsg,
      ]
    );
  }

  public function mensagens_novo($page = 0)
  {
    $countMsg = $this->msg->countNew();
    $this->load->library(['pagination']);

    $config = array(
      "base_url"       => base_url(['mensagens', 'novo']),
      "per_page"       => 5,
      "num_links"     => 2,
      "use_page_numbers"   => TRUE,
      "uri_segment"     => 3,
      "total_rows"     => $this->msg->countNew(),
    );
    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    $config['attributes'] = ['class' => 'page-link'];
    $config['first_link'] = false;
    $config['last_link'] = false;
    $config['first_tag_open'] = '<li class="page-item">';
    $config['first_tag_close'] = '</li>';
    $config['prev_link'] = '&laquo';
    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';
    $config['next_link'] = '&raquo';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li class="page-item">';
    $config['last_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
    $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';

    if ($page !== 0) {
      $cont = $page - 1;
      $limite = $cont * 5;
    } else {
      $limite = 0;
    }

    $mensagens = $this->msg->getAll($config['per_page'], $limite, TRUE);

    if ($page != 0) {
      if (empty($mensagens)) {
        redirect(base_url(['mensagens/novo']), 'refresh');
      }
    }

    $this->pagination->initialize($config);

    $this->load->view(
      'only_page',
      [
        "call" => "adm/mensagens_novo",
        "mensagens" => $mensagens,
        "countMsg" => $countMsg,
      ]
    );
  }

  public function del_msg($id)
  {
    $data['response'] = $this->msg->delete($id);
    $data['count'] = $this->msg->countNew();
    echo json_encode($data);
  }

  public function marcar_msg_novo()
  {
    $data['response'] = $this->msg->update(['confirmation' => 0], $this->input->post('id'));
    $data['count'] = $this->msg->countNew();
    echo json_encode($data);
  }

  public function open_msg()
  {

    $data['response'] = $this->msg->update(['confirmation' => 1], $this->input->post('id'));
    $data['count'] = $this->msg->countNew();
    echo json_encode($data);
  }

  public function publicacoes($page = 0)
  {
    $countMsg = $this->msg->countNew();
    $this->load->library(['pagination']);

    $config = array(
      "base_url"       => base_url(['publicacoes']),
      "per_page"       => 5,
      "num_links"     => 2,
      "use_page_numbers"   => TRUE,
      "uri_segment"     => 2,
      "total_rows"     => $this->post->countAll(),
    );
    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    $config['attributes'] = ['class' => 'page-link'];
    $config['first_link'] = false;
    $config['last_link'] = false;
    $config['first_tag_open'] = '<li class="page-item">';
    $config['first_tag_close'] = '</li>';
    $config['prev_link'] = '&laquo';
    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';
    $config['next_link'] = '&raquo';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li class="page-item">';
    $config['last_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
    $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';

    if ($page !== 0) {
      $cont = $page - 1;
      $limite = $cont * 5;
    } else {
      $limite = 0;
    }

    $posts = $this->post->getAll($config['per_page'], $limite);

    if ($page != 0) {
      if (empty($posts)) {
        redirect(base_url(['publicacoes']), 'refresh');
      }
    }

    $user = $this->user->getAll();

    $this->pagination->initialize($config);
    $this->load->view(
      'only_page',
      [
        "call" => "adm/publicacoes",
        "posts" => $posts,
        "countMsg" => $countMsg,
        "user" => $user,
      ]
    );
  }

  public function post_add()
  {

    $string = $this->input->post('title');
    $string = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $string);
    $string = preg_replace(array('/[ ]/', '/[^A-Za-z0-9\-]/'), array('', ''), $string);

    if (!empty($_FILES['arquivo']['size'])) {
      $url = $this->post->uploadImg('arquivo', $string);
    } else {
      $url = "angel-default.jpg";
    }

    $insert = $this->post->insert(
      [
        'titulo' => $this->input->post('title'),
        'previa' => $this->input->post('preview'),
        'capa' => $url,
        'data' => date('Y-m-d H:i:s'),
        'user' => $_SESSION['user_id'],
      ]
    );

    if ($insert) {

      $add = $this->user->get($_SESSION['user_id']);
      $total = $add->numpost + 1;
      $this->user->userUpdate(['numpost' => $total], $_SESSION['user_id']);
      $this->session->set_flashdata('error', 'success');
      $this->session->set_flashdata('msg', 'Publicação adicionada com sucesso!');
    } else {
      $this->session->set_flashdata('error', 'danger');
      $this->session->set_flashdata('msg', 'Não foi possível adicionar a publicação!');
    }

    redirect('publicacoes', 'refresh');
  }

  public function page_edit($id)
  {
    $countMsg = $this->msg->countNew();
    $post = $this->post->get($id);
    $user = $this->user->getAll();
    $status = $this->post->getStatus($post->id);

    $this->load->view(
      'only_page',
      [
        "call" => "adm/page_edit",
        "post" => $post,
        "countMsg" => $countMsg,
        "user" => $user,
        "status" => $status,
      ]
    );
  }

  public function save_img()
  {
    $string = $this->input->post('caminho');
    $string = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $string);
    $string = preg_replace(array('/[ ]/', '/[^A-Za-z0-9\-]/'), array('', ''), $string);
    $caminho = $this->input->post('id');
    $uploaddir = './img/post/' . $caminho;
    if (!is_dir($uploaddir)) {
      mkdir($uploaddir, 0777);
    }
    $url = $this->post->subirImg('image', $caminho);
    echo base_url(['img', 'post', $caminho, $url]);
  }

  public function save_status()
  {
    $check = $this->post->getStatus($this->input->post('id'));

    if (!empty($check)) {
      $this->post->updateStatus(
        [
          'data' => $this->input->post('data'),
          'date' => date('Y-m-d H:i:s'),
        ],
        $this->input->post('id')
      );
    } else {
      $this->post->insertStatus(
        [
          'post_id' => $this->input->post('id'),
          'data' => $this->input->post('data'),
          'date' => date('Y-m-d H:i:s'),
        ]
      );
    }
    echo true;
  }

  public function page_update($id)
  {
    // preparação das variaveis
    $find = base_url(['img', 'post', $id]) . "/";
    $img = explode($find, $this->input->post('imagens'));
    array_shift($img);
    $dir = './img/post/' . $id . '/';
    if (is_dir($dir)) {
      $directory_iterator = new \RecursiveDirectoryIterator(
        $dir,
        \FilesystemIterator::SKIP_DOTS
      );
      $iterator = new \RecursiveIteratorIterator($directory_iterator);
      $it = "";
      foreach ($iterator as $file) {
        $it .= $file;
      }
      $imgComp = explode($dir, $it);
      array_shift($imgComp);
      // function of array compare
      for ($i = 0; $i < count($imgComp); $i++) {
        if (!$this->post->equal($img, $imgComp[$i])) {
          unlink($dir . $imgComp[$i]);
        }
      }
    }
    $string = $this->input->post('title');
    $string = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $string);
    $string = preg_replace(array('/[ ]/', '/[^A-Za-z0-9\-]/'), array('', ''), $string);
    $data['texto'] = $this->input->post('editor');
    $data['titulo'] = $this->input->post('title');
    $data['previa'] = $this->input->post('preview');
    $data['datePost'] = date('Y-m-d H:i:s');
    if (!empty($_FILES['arquivo']['size'])) {
      $url = $this->post->uploadImg('arquivo', $string);
      $data['capa'] = $url;
    }
    $update = $this->post->update($data, $id);
    if ($update) {
      $this->session->set_flashdata('error', 'success');
      $this->session->set_flashdata('msg', 'Publicação atualizada com sucesso! Agora ela pode ser publicada!');
      $this->post->deleteStatus($id);
    } else {
      $this->session->set_flashdata('error', 'danger');
      $this->session->set_flashdata('msg', 'Não foi possível atualizar a publicação!');
    }
    redirect('publicacoes', 'refresh');
  }

  public function post_del($id)
  {
    $path = './img/post/' . $id;
    if (is_dir($path)) {
      $this->post->delTree('./img/post/' . $id);
    }
    $this->post->deleteStatus($id);
    $post = $this->post->get($id);
    if ($post->capa !== "angel-default.jpg") {
      unlink("./img/capa/" . $post->capa);
    }
    $add = $this->user->get($_SESSION['user_id']);
    $total = $add->numpost - 1;
    $this->user->userUpdate(['numpost' => $total], $_SESSION['user_id']);
    echo $this->post->delete($id);
  }

  public function publicar()
  {
    if ($this->input->post('mod') == "Esconder") {
      $update = $this->post->update(['publicar' => 1], $this->input->post('id'));
      $data = "Publicar";
    } else {
      $update = $this->post->update(['publicar' => 0], $this->input->post('id'));
      $data = "Esconder";
    }

    echo $data;
  }

  public function pub_search($page = 0)
  {
    $this->load->library(['pagination']);
    $countMsg = $this->msg->countNew();

    if ($this->input->post('busca')) {
      $busca = $this->input->post('busca');
      $this->session->set_userdata('search', $this->input->post('busca'));
    } elseif ($_SESSION['search']) {
      $busca = $_SESSION['search'];
    } else {
      redirect(base_url(['publicacoes']), 'refresh');
    }

    $config = array(
      "base_url"       => base_url(['publicacoes', 'busca']),
      "per_page"       => 5,
      "num_links"     => 2,
      "use_page_numbers"   => TRUE,
      "uri_segment"     => 3,
      "total_rows"     => $this->post->countSearch($busca),
    );
    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    $config['attributes'] = ['class' => 'page-link'];
    $config['first_link'] = false;
    $config['last_link'] = false;
    $config['first_tag_open'] = '<li class="page-item">';
    $config['first_tag_close'] = '</li>';
    $config['prev_link'] = '&laquo';
    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';
    $config['next_link'] = '&raquo';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li class="page-item">';
    $config['last_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
    $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';

    if ($page !== 0) {
      $cont = $page - 1;
      $limite = $cont * 5;
    } else {
      $limite = 0;
    }

    $posts = $this->post->getSearch($config['per_page'], $limite, $busca);

    if ($page != 0) {
      if (empty($posts)) {
        redirect(base_url(['publicacoes']), 'refresh');
      }
    }

    $user = $this->user->getAll();

    $this->pagination->initialize($config);
    $this->load->view(
      'only_page',
      [
        "call" => "adm/publicacoes",
        "posts" => $posts,
        "countMsg" => $countMsg,
        "user" => $user,
      ]
    );
  }

  public function update_radio()
  {
    $this->post->radio(['link' => $this->input->post('link')]);
  }

  public function logoff()
  {
    $this->user->deslogar();
  }
}
