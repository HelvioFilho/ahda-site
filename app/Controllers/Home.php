<?php

namespace App\Controllers;

use App\Models\UserModel;

class Home extends BaseController
{
  public function __construct()
  {
    $userModel = new UserModel();
    $userModel->verifyLogin();
  }

  public function index()
  {
    session();
    if (isset($_SESSION['user_id'])) {
      return redirect()->to('home');
    }

    return view('index');
  }

  public function PrivacyPolicy(){
    return view('privacy_policy');
  }
}
