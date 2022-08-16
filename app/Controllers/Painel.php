<?php

namespace App\Controllers;

use App\Models\MessageModel;
use App\Models\PostModel;
use App\Models\RadioModel;
use App\Models\UserModel;

class Painel extends BaseController
{

  public function __construct()
  {
    $userModel = new UserModel();
    $userModel->verifyLogin();
  }

  public function home()
  {
    $session = session();
    if (!isset($_SESSION['user_id'])) {
      return redirect()->to('/');
    }

    $userModel = new UserModel();
    $messageModel = new MessageModel();
    $postModel = new PostModel();
    $radioModel = new RadioModel();

    $countMsg =  $messageModel->like('is_read', 0)->countAllResults();
    $posts = $postModel->orderBy('id', 'DESC')->paginate(5);
    $mensagens = $messageModel->orderBy('id', 'DESC')->paginate(5);
    $user = $userModel->findAll();
    $url_link = $radioModel->where('id', 1)->first();

    return view(
      'only_page',
      [
        "call" => "adm/index",
        "countMsg" => $countMsg,
        "posts" => $posts,
        "user" => $user,
        "mensagens" => $mensagens,
        "url_link" => $url_link,
        "session" => $session,
        "uri" => service('uri'),
      ]
    );
  }

  public function myAccount()
  {
    $session = session();
    if (!isset($_SESSION['user_id'])) {
      return redirect()->to('/');
    }

    $userModel = new UserModel();
    $messageModel = new MessageModel();

    $countMsg = $messageModel->like('is_read', 0)->countAllResults();
    $data = $userModel->where('user_id', $_SESSION['user_id'])->first();

    return view(
      'only_page',
      [
        "call" => "adm/minha_conta",
        "data" => $data,
        "session" => $session,
        "uri" => service('uri'),
        "countMsg" => $countMsg,
      ]
    );
  }

  public function updateAccount()
  {
    $userModel = new UserModel();
    $session = session();

    $total = [
      'name'   => $this->request->getPost('name'),
      'email' => $this->request->getPost('email'),
      'sobre'  => $this->request->getPost('sobre'),
      'erro'   => false,
      'msg'  => '',
    ];
    $verify = $userModel->where('email', strtolower($total['email']))->first();
    if (!isset($verify) || $_SESSION['email'] == $total['email']) {

      $dataUp = [
        'username'   => $total['name'],
        'email'   => $total['email'],
        'about'    => $total['sobre'],
      ];
      $userModel->where('email', $_SESSION['email'])->set($dataUp)->update();
      $newSession = [
        'usuario_logado' => $total['name'],
        'email' => $total['email']
      ];

      $session->set($newSession);
    } else {
      $total['erro'] = true;
      $total['msg'] = '* Email já existe!';
    }

    echo json_encode($total);
  }

  public function updateImage()
  {
    $userModel = new UserModel();
    $session = session();

    if (!empty($this->request->getPost('img')))
      unlink("img/user/" . $this->request->getPost('img'));

    $file = $this->request->getFile('arquivo');
    $url = $userModel->uploadImg($file);
    if ($url) {
      $update = $userModel->where('email', $_SESSION['email'])->set(["img" => $url])->update();
      if ($update) {
        $session->setFlashdata('error', 'success');
      } else {
        $session->setFlashdata('error', 'Erro ao atualizar a imagem.');
      }
    } else {
      $session->setFlashdata('error', 'Erro ao salvar a imagem');
    }
    return redirect()->to('minha_conta');
  }

  public function usuarios()
  {
    $session = session();
    if (!isset($_SESSION['user_id'])) {
      return redirect()->to('/');
    }

    $userModel = new UserModel();
    $messageModel = new MessageModel();

    $countMsg = $messageModel->like('is_read', 0)->countAllResults();
    $user = $userModel->findAll();

    return view(
      'only_page',
      [
        "call" => "adm/usuarios",
        "users" => $user,
        "countMsg" => $countMsg,
        "session" => $session,
        "uri" => service('uri'),
      ]
    );
  }

  public function add_user()
  {
    $session = session();
    $userModel = new UserModel();

    $gera = mt_rand(1, 9999);
    $data = [
      'user_id' => $gera,
      'acesso' => $this->request->getPost('select'),
      'username' => $this->request->getPost('name'),
      'email' => $this->request->getPost('email'),
      'passwd' => password_hash("BraVe123#", PASSWORD_DEFAULT),
      'criacao' => date('Y-m-d H:i:s'),
    ];

    if ($userModel->insert($data)) {
      $session->setFlashdata('error', 'success');
      $session->setFlashdata('msg', 'Usuário adicionado com sucesso!');
    } else {
      $session->setFlashdata('error', 'danger');
      $session->setFlashdata('msg', 'Não foi possível adicionar o usuário!');
    }
    return redirect()->to('usuarios');
  }

  public function del_user($del)
  {
    $userModel = new UserModel();

    $dados = $userModel->where('user_id', $del)->first();
    if ($dados->img) {
      unlink("img/user/" . $dados->img);
    }
    echo $userModel->delete(['id' => $del]);
  }

  public function updated_access()
  {
    $userModel = new UserModel();

    if ($this->request->getPost('acesso') == 2) {
      $data['acesso'] = 2;
      $data['padrao'] = "<br>Agora é um <b>moderador</b>, podendo criar e excluir usuários, fazer publicações e publicar no aplicativo!";
    } else {
      $data['acesso'] = 3;
      $data['padrao'] = "<br>Agora é um <b>usuário normal</b>, podendo fazer apenas publicações no aplicativo!";
    }

    $verify = $userModel->where('user_id', $this->request->getPost('id'))->set(['access' => $data['acesso']])->update();

    if ($verify) {
      $data['error'] = true;
    } else {
      $data['error'] = false;
    }

    echo json_encode($data);
  }

  public function disabled()
  {
    $userModel = new UserModel();

    if ($this->request->getPost('des') == 0) {
      $data['des'] = 1;
      $data['msg'] = "Desativar";
      $data['padrao'] = "ativado";
    } else {
      $data['des'] = 0;
      $data['msg'] = "Ativar";
      $data['padrao'] = "desativado";
    }

    $verify = $userModel->where('user_id', $this->request->getPost('id'))->set(['is_disabled' => $data['des']])->update();

    if ($verify) {
      $data['error'] = true;
    } else {
      $data['error'] = false;
    }

    echo json_encode($data);
  }

  public function message()
  {
    $session = session();
    if (!isset($_SESSION['user_id'])) {
      return redirect()->to('/');
    }

    $messageModel = new MessageModel();

    $countMsg = $messageModel->like('is_read', 0)->countAllResults();
    $message = $messageModel->orderBy('id', 'DESC')->paginate(5);
    $pager = $messageModel->pager;

    return view(
      'only_page',
      [
        "call" => "adm/message",
        "mensagens" => $message,
        "countMsg" => $countMsg,
        "pager" => $pager,
        "session" => $session,
        "uri" => service('uri'),
      ]
    );
  }

  public function newMessage()
  {

    $session = session();
    if (!isset($_SESSION['user_id'])) {
      return redirect()->to('/');
    }

    $messageModel = new MessageModel();

    $countMsg = $messageModel->like('is_read', 0)->countAllResults();
    $message = $messageModel->like('is_read', 0)->orderBy('id', 'DESC')->paginate(5);
    $pager = $messageModel->pager;

    return view(
      'only_page',
      [
        "call" => "adm/newMessage",
        "mensagens" => $message,
        "countMsg" => $countMsg,
        "pager" => $pager,
        "session" => $session,
        "uri" => service('uri'),
      ]
    );
  }

  public function deleteMessage($id)
  {
    $messageModel = new MessageModel();

    $data['response'] = $messageModel->delete(['id' => $id]);
    $data['count'] = $messageModel->like('is_read', 0)->countAllResults();
    echo json_encode($data);
  }

  public function markMessage()
  {
    $messageModel = new MessageModel();
    
    $data['response'] = $messageModel->where('id', $this->request->getPost('id'))->set(['is_read' => 0])->update();
    $data['count'] = $messageModel->like('is_read', 0)->countAllResults();
    echo json_encode($data);
  }

  public function OpenMessage()
  {
    $messageModel = new MessageModel();

    $data['response'] = $messageModel->where('id', $this->request->getPost('id'))->set(['is_read' => 1])->update();
    $data['count'] = $messageModel->like('is_read', 0)->countAllResults();
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
    $session = session();
    $string = $this->request->getPost('title');
    $string = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $string);
    $string = preg_replace(array('/[ ]/', '/[^A-Za-z0-9\-]/'), array('', ''), $string);

    if (!empty($_FILES['arquivo']['size'])) {
      $url = $this->post->uploadImg('arquivo', $string);
    } else {
      $url = "angel-default.jpg";
    }

    $insert = $this->post->insert(
      [
        'titulo' => $this->request->getPost('title'),
        'previa' => $this->request->getPost('preview'),
        'capa' => $url,
        'data' => date('Y-m-d H:i:s'),
        'user' => $_SESSION['user_id'],
      ]
    );

    if ($insert) {

      $add = $this->user->get($_SESSION['user_id']);
      $total = $add->numpost + 1;
      $this->user->userUpdate(['numpost' => $total], $_SESSION['user_id']);
      $session->setFlashdata('error', 'success');
      $session->setFlashdata('msg', 'Publicação adicionada com sucesso!');
    } else {
      $session->setFlashdata('error', 'danger');
      $session->setFlashdata('msg', 'Não foi possível adicionar a publicação!');
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
    $string = $this->request->getPost('caminho');
    $string = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $string);
    $string = preg_replace(array('/[ ]/', '/[^A-Za-z0-9\-]/'), array('', ''), $string);
    $caminho = $this->request->getPost('id');
    $uploaddir = './img/post/' . $caminho;
    if (!is_dir($uploaddir)) {
      mkdir($uploaddir, 0777);
    }
    $url = $this->post->subirImg('image', $caminho);
    echo base_url(['img', 'post', $caminho, $url]);
  }

  public function save_status()
  {
    $check = $this->post->getStatus($this->request->getPost('id'));

    if (!empty($check)) {
      $this->post->updateStatus(
        [
          'data' => $this->request->getPost('data'),
          'date' => date('Y-m-d H:i:s'),
        ],
        $this->request->getPost('id')
      );
    } else {
      $this->post->insertStatus(
        [
          'post_id' => $this->request->getPost('id'),
          'data' => $this->request->getPost('data'),
          'date' => date('Y-m-d H:i:s'),
        ]
      );
    }
    echo true;
  }

  public function page_update($id)
  {
    $session = session();
    // preparação das variaveis
    $find = base_url(['img', 'post', $id]) . "/";
    $img = explode($find, $this->request->getPost('imagens'));
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
    $string = $this->request->getPost('title');
    $string = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $string);
    $string = preg_replace(array('/[ ]/', '/[^A-Za-z0-9\-]/'), array('', ''), $string);
    $data['texto'] = $this->request->getPost('editor');
    $data['titulo'] = $this->request->getPost('title');
    $data['previa'] = $this->request->getPost('preview');
    $data['datePost'] = date('Y-m-d H:i:s');
    if (!empty($_FILES['arquivo']['size'])) {
      $url = $this->post->uploadImg('arquivo', $string);
      $data['capa'] = $url;
    }
    $update = $this->post->update($data, $id);
    if ($update) {
      $session->setFlashdata('error', 'success');
      $session->setFlashdata('msg', 'Publicação atualizada com sucesso! Agora ela pode ser publicada!');
      $this->post->deleteStatus($id);
    } else {
      $session->setFlashdata('error', 'danger');
      $session->setFlashdata('msg', 'Não foi possível atualizar a publicação!');
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
    if ($this->request->getPost('mod') == "Esconder") {
      $update = $this->post->update(['publicar' => 1], $this->request->getPost('id'));
      $data = "Publicar";
    } else {
      $update = $this->post->update(['publicar' => 0], $this->request->getPost('id'));
      $data = "Esconder";
    }

    echo $data;
  }

  public function pub_search($page = 0)
  {
    $session = session();
    $this->load->library(['pagination']);
    $countMsg = $this->msg->countNew();

    if ($this->request->getPost('busca')) {
      $busca = $this->request->getPost('busca');
      $session->set_userdata('search', $this->request->getPost('busca'));
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
    $this->post->radio(['link' => $this->request->getPost('link')]);
  }

  public function logoff()
  {
    $userModel = new UserModel();
    $userModel->logoff("Você acabou de sair do painel administrativo!");
    return redirect()->to('/');
  }
}
