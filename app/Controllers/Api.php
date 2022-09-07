<?php

namespace App\Controllers;

use App\Models\ImageModel;
use App\Models\MessageModel;
use App\Models\PostModel;
use App\Models\UserModel;

class Api extends BaseController
{

    public function getPost()
    {
        $postModel = new PostModel();
        $userModel = new UserModel();

        $page = $this->request->getGet('page');
        $size = $this->request->getGet('size');

        if ($_ENV['KEY_API'] === $this->request->getGet('key')) {

            $offset = $page - 1;
            $limit = $offset * $size;
            $postData = $postModel->orderBy('id', 'DESC')->getWhere(['is_published' => 1], $size, $limit)->getResult();
            $countPost = ceil($postModel->like('is_published', 1)->countAllResults() / $size);
            $user = $userModel->findAll();

            foreach ($postData as $post) {
                $post->cover = base_url(['img', 'capa', $post->cover]);
                $post->date_post = date('d/m/Y H:i', strtotime($post->date_post));
                foreach ($user as $us) {
                    if ($post->user === $us->user_id) {
                        $img = $us->img ? $us->img : 'generic.jpeg';
                        $userInfo = (object) array(
                            'name' => $us->username,
                            'image' => base_url(['img', 'user', $img]),
                            'about' => $us->about
                        );
                        $post->user = $userInfo;
                    }
                }
            }

            $data = [
                'data' => $postData,
                'count' => $countPost,
                'error' => false
            ];

            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'Chave incorreta ou inexistente']);
        }
    }

    public function search()
    {
        $postModel = new PostModel();
        $userModel = new UserModel();

        $page = $this->request->getGet('page');
        $size = $this->request->getGet('size');
        $search = $this->request->getGet('search');
        if ($_ENV['KEY_API'] === $this->request->getGet('key')) {
            $offset = $page - 1;
            $limit = $offset * $size;
            $searchFields = [
                'title' => $search,
                'text' => $search,
                'preview' => $search
            ];

            $postData = $postModel->groupStart()->orLike($searchFields)->groupEnd()->orderBy('id', 'DESC')->getWhere(['is_published' => 1], $size, $limit)->getResult();
            $countPost = ceil($postModel->groupStart()->orLike($searchFields)->groupEnd()->like('is_published', 1)->countAllResults() / $size);

            $user = $userModel->findAll();

            foreach ($postData as $post) {
                $post->cover = base_url(['img', 'capa', $post->cover]);
                $post->date_post = date('d/m/Y H:i', strtotime($post->date_post));
                foreach ($user as $us) {
                    if ($post->user === $us->user_id) {
                        $img = $us->img ? $us->img : 'generic.jpeg';
                        $userInfo = (object) array(
                            'name' => $us->username,
                            'image' => base_url(['img', 'user', $img]),
                            'about' => $us->about
                        );
                        $post->user = $userInfo;
                    }
                }
            }

            $data = [
                'data' => $postData,
                'count' => $countPost,
                'error' => false
            ];

            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'Chave incorreta ou inexistente']);
        }
    }

    public function getImageGallery()
    {
        $imageModel = new ImageModel();
        $id = $this->request->getGet('id');
        if ($_ENV['KEY_API'] === $this->request->getGet('key')) {
            $images = $imageModel->like(['post_id' => $id])->findAll();
            foreach ($images as $image) {
                $image->path = base_url(['img', $image->path]);
            }
            $data = [
                'images' => $images,
                'error' => false,
            ];
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'Chave incorreta ou inexistente']);
        }
    }

    public function setMessage()
    {
        $messageModel = new MessageModel();

        $message = $this->request->getJSON(true);
        if ($_ENV['KEY_API'] === $message['key']) {
            if ($messageModel->insert($message)) {
                $data = [
                    'error' => false,
                ];
            } else {
                $data = [
                    'error' => true,
                ];
            }
            echo json_encode($data);
        } else {
            echo json_encode(
                [
                    'error' => true,
                    'message' => 'Chave incorreta ou inexistente, entre em contato com o administrador do sistema!',
                ]
            );
        }
    }
}
