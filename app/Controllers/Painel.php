<?php

namespace App\Controllers;

use App\Models\ImageModel;
use App\Models\MessageModel;
use App\Models\PostModel;
use App\Models\RadioModel;
use App\Models\StatusModel;
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
        "call" => "adm/myAccount",
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

  public function users()
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
        "call" => "adm/users",
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
      'access' => $this->request->getPost('select'),
      'username' => $this->request->getPost('name'),
      'email' => $this->request->getPost('email'),
      'passwd' => password_hash("BraVe123#", PASSWORD_DEFAULT),
      'created_at' => date('Y-m-d H:i:s'),
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
    echo $userModel->where('id', $del)->delete();
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

    $data['response'] = $messageModel->where('id', $id)->delete();
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

  public function publications()
  {
    $session = session();
    if (!isset($_SESSION['user_id'])) {
      return redirect()->to('/');
    }
    $userModel = new UserModel();
    $postModel = new PostModel();
    $messageModel = new MessageModel();

    $countMsg = $messageModel->like('is_read', 0)->countAllResults();
    $posts = $postModel->orderBy('id', 'DESC')->paginate(3);
    $countPost = $postModel->countAllResults();
    $pager = $postModel->pager;
    $user = $userModel->findAll();

    return view(
      'only_page',
      [
        "call" => "adm/publications",
        "posts" => $posts,
        "countMsg" => $countMsg,
        "countPost" => $countPost,
        "user" => $user,
        "pager" => $pager,
        "session" => $session,
        "uri" => service('uri'),
      ]
    );
  }

  public function addPost()
  {
    $session = session();
    $postModel = new PostModel();
    $userModel = new UserModel();

    $string = $this->request->getPost('title');
    $string = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $string);
    $string = preg_replace(array('/[ ]/', '/[^A-Za-z0-9\-]/'), array('', ''), $string);

    if (!empty($_FILES['arquivo']['size'])) {
      $file = $this->request->getFile('arquivo');
      $url = $postModel->uploadImg($file, 'capa/');
    } else {
      $url = "angel-default.jpg";
    }

    $data = [
      'title' => $this->request->getPost('title'),
      'preview' => $this->request->getPost('preview'),
      'cover' => $url,
      'date' => date('Y-m-d H:i:s'),
      'user' => $_SESSION['user_id'],
    ];

    if ($postModel->insert($data)) {
      $add = $userModel->where('user_id', $_SESSION['user_id'])->first();
      $total = $add->count_post + 1;
      $userModel->where('user_id', $_SESSION['user_id'])->set(['count_post' => $total])->update();
      $session->setFlashdata('error', 'success');
      $session->setFlashdata('msg', 'Publicação adicionada com sucesso!');
    } else {
      $session->setFlashdata('error', 'danger');
      $session->setFlashdata('msg', 'Não foi possível adicionar a publicação!');
    }
    return redirect()->to('publicacoes');
  }

  public function pageEdit($id)
  {
    $session = session();
    if (!isset($_SESSION['user_id'])) {
      return redirect()->to('/');
    }

    $userModel = new UserModel();
    $messageModel = new MessageModel();
    $postModel = new PostModel();
    $statusModel = new StatusModel();
    $imageModel = new ImageModel();

    $countMsg = $messageModel->like('is_read', 0)->countAllResults();
    $post = $postModel->where('id', $id)->first();
    $user = $userModel->findAll();
    $status = $statusModel->where('post_id', $id)->first();

    if (empty($post)) {
      $session->setFlashdata('error', 'danger');
      $session->setFlashdata('msg', 'A publicação não existe ou foi apagada!');
      return redirect()->to('publicacoes');
    }

    $imageCarousel = $imageModel->where('post_id', $id)->findAll();

    return view(
      'only_page',
      [
        "call" => "adm/editPage",
        "post" => $post,
        "countMsg" => $countMsg,
        "user" => $user,
        "status" => $status,
        "imageCarousel" => $imageCarousel,
        "session" => $session,
        "uri" => service('uri'),
      ]
    );
  }

  public function saveImage()
  {
    $postModel = new PostModel();
    // verificar necessidade
    $string = $this->request->getPost('caminho');
    $string = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $string);
    $string = preg_replace(array('/[ ]/', '/[^A-Za-z0-9\-]/'), array('', ''), $string);
    // ~~~
    $folder = $this->request->getPost('id');
    $uploaddir = './img/post/' . $folder;
    if (!is_dir($uploaddir)) {
      mkdir($uploaddir, 0777);
    }
    $file = $this->request->getFile('image');

    $url = $postModel->uploadImg($file, 'post/' . $folder);
    echo base_url(['img', 'post', $folder, $url]);
  }

  public function saveStatus()
  {
    $statusModel = new StatusModel();

    $check = $statusModel->where('post_id', $this->request->getPost('id'))->first();

    if (!empty($check)) {
      $statusModel->where('post_id', $this->request->getPost('id'))->set(
        [
          'data' => $this->request->getPost('data'),
          'date' => date('Y-m-d H:i:s'),
        ]
      )->update();
    } else {
      $statusModel->insert(
        [
          'post_id' => $this->request->getPost('id'),
          'data' => $this->request->getPost('data'),
          'date' => date('Y-m-d H:i:s'),
        ]
      );
    }
    echo true;
  }

  public function pageUpdate($id)
  {
    $session = session();
    $postModel = new PostModel();
    $statusModel = new StatusModel();

    // preparation of variables
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
        if (!$postModel->equal($img, $imgComp[$i])) {
          unlink($dir . $imgComp[$i]);
        }
      }
    }

    $data['text'] = $this->request->getPost('editor');
    $data['title'] = $this->request->getPost('title');
    $data['preview'] = $this->request->getPost('preview');
    $data['date_post'] = date('Y-m-d H:i:s');

    if (!empty($_FILES['arquivo']['size'])) {
      $file = $this->request->getFile('arquivo');
      $url = $postModel->uploadImg($file, 'capa/');
      $data['cover'] = $url;
    }

    $update = $postModel->where('id', $id)->set($data)->update();

    if ($update) {
      $session->setFlashdata('error', 'success');
      $session->setFlashdata('msg', 'Publicação atualizada com sucesso! Agora ela pode ser publicada!');
      $statusModel->where('post_id', $id)->delete();
    } else {
      $session->setFlashdata('error', 'danger');
      $session->setFlashdata('msg', 'Não foi possível atualizar a publicação!');
    }

    return redirect()->to('publicacoes');
  }

  public function deletePost($id)
  {
    $postModel = new PostModel();
    $statusModel = new StatusModel();
    $userModel = new UserModel();
    $imageModel = new ImageModel();

    $path = './img/post/' . $id;
    if (is_dir($path)) {
      $postModel->clearPostImage('./img/post/' . $id);
    }
    $statusModel->where('post_id', $id)->delete();

    $post = $postModel->where('id', $id)->first();
    if ($post->cover !== "angel-default.jpg") {
      unlink("./img/capa/" . $post->cover);
    }

    $add = $userModel->where('user_id', $post->user)->first();
    if ($add) {
      $total = $add->count_post - 1;
      $userModel->where('user_id', $post->user)->set(['count_post' => $total])->update();
    }

    $imageModel->where('post_id', $id)->delete();

    echo $postModel->where('id', $id)->delete();
  }

  public function publish()
  {
    $postModel = new PostModel();
    $id = $this->request->getPost('id');

    if ($this->request->getPost('mod') == "Esconder") {
      $postModel->where('id', $id)->set(['is_published' => 1])->update();
      $data = "Publicar";
    } else {
      $postModel->where('id', $id)->set(['is_published' => 0])->update();
      $data = "Esconder";
    }

    echo $data;
  }

  public function searchPublication()
  {
    $session = session();
    if (!isset($_SESSION['user_id'])) {
      return redirect()->to('/');
    }
    $userModel = new UserModel();
    $postModel = new PostModel();
    $messageModel = new MessageModel();

    $countMsg = $messageModel->like('is_read', 0)->countAllResults();
    $countPost = $postModel->countAllResults();
    $pager = $postModel->pager;
    $user = $userModel->findAll();

    if ($this->request->getPost('busca')) {
      $search = $this->request->getPost('busca');
      $session->set(['search' => $search]);
    } elseif ($_SESSION['search']) {
      $search = $_SESSION['search'];
    } else {
      return redirect()->to('publicacoes');
    }

    $searchFields = [
      'title' => $search,
      'text' => $search,
      'preview' => $search
    ];

    $posts = $postModel->orlike($searchFields)->orderBy('id', 'DESC')->paginate(3);
    $pager = $postModel->pager;

    return view(
      'only_page',
      [
        "call" => "adm/publications",
        "posts" => $posts,
        "countMsg" => $countMsg,
        "countPost" => $countPost,
        "user" => $user,
        "pager" => $pager,
        "session" => $session,
        "uri" => service('uri'),
      ]
    );
  }

  public function addCarousel()
  {
    $session = session();
    $imageModel = new ImageModel();
    $postModel = new PostModel();
    $folder = $this->request->getPost('id');
    $file = $this->request->getFile('arquivoCarousel');

    $uploaddir = './img/post/' . $folder;
    if (!is_dir($uploaddir)) {
      mkdir($uploaddir, 0777);
    }

    $url = $postModel->uploadImg($file, 'post/' . $folder);
    $data = [
      "path" => "/post" . "/" . $folder . "/" . $url,
      "post_id" => $folder
    ];

    if ($imageModel->insert($data)) {
      $session->setFlashdata('error', 'success');
      $session->setFlashdata('msg', 'Imagem adicionada com sucesso!');
    } else {
      $session->setFlashdata('error', 'danger');
      $session->setFlashdata('msg', 'Algo deu errado e não foi possível adicionar a imagem!');
    }

    return redirect()->to('publicacao/' . $folder);
  }

  public function deleteCarousel($folder)
  {
    $session = session();
    $imageModel = new ImageModel();
    $id = $this->request->getPost('id');

    $image = $imageModel->where('id', $id)->first();
    if ($image) {
      unlink("./img" . $image->path);
    }

    if ($imageModel->where('id', $id)->delete()) {
      $session->setFlashdata('error', 'success');
      $session->setFlashdata('msg', 'Imagem deletada com sucesso!');
    } else {
      $session->setFlashdata('error', 'danger');
      $session->setFlashdata('msg', 'Algo deu errado e não foi possível deletar a imagem!');
    }

    return redirect()->to('publicacao/' . $folder);
  }

  public function radioUpdate()
  {
    $radioModel = new RadioModel();

    $newData = ['link' => $this->request->getPost('link')];
    $checkData = $radioModel->where('id', 1)->first();
    if (!isset($checkData)) {
      $radioModel->insert($newData);
    } else {
      $radioModel->where('id', 1)->set($newData)->update();
    }
    echo true;
  }

  public function logoff()
  {
    $userModel = new UserModel();
    $userModel->logoff("Você acabou de sair do painel administrativo!");
    return redirect()->to('/');
  }
}
