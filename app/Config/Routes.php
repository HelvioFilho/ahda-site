<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
//$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

// admin
$routes->get('adm/criar_user', 'Admin::criar_adm');
$routes->post('adm/verificar', 'Admin::verificarUser');
$routes->post('adm/logar', 'Admin::logIn');
$routes->get('adm/recuperar_senha/(:any)/(:any)', 'Admin::changePassword/$1/$2');
$routes->post('adm/recuperar_senha/(:any)', 'Admin::changePassword/$1');
$routes->post('adm/recuperar', 'Admin::recoverPassword');

// Rotas painel
$routes->get('logoff', 'Painel::logoff');
$routes->get('home', 'Painel::home');

// Minha conta
$routes->get('minha_conta', 'Painel::myAccount');
$routes->post('minha_conta/atualizar/contato', 'Painel::updateAccount');
$routes->post('minha_conta/atualizar_img', 'Painel::updateImage');

// usuários
$routes->get('usuarios', 'Painel::users');
$routes->post('user/add', 'Painel::add_user');
$routes->get('delete_user/(:num)', 'Painel::del_user/$1');
$routes->post('desabilitar_user', 'Painel::disabled');
$routes->post('alterar_acesso', 'Painel::updated_access');

//mensagens
$routes->get('mensagens', 'Painel::message');
$routes->get('mensagens/novo', 'Painel::newMessage');
$routes->get('delete_mensagem/(:num)', 'Painel::deleteMessage/$1');
$routes->post('marcar_novo', 'Painel::markMessage');
$routes->post('abrir_mensagem', 'Painel::OpenMessage');

// publicações
$routes->get('publicacoes', 'Painel::publications');
$routes->post('post/add', 'Painel::addPost');

// editar publicação
$routes->get('publicacao/(:num)', 'Painel::pageEdit/$1');

// salvar imagem post
$routes->post('save/img', 'Painel::saveImage');

// salvar conteúdo automaticamente
$routes->post('save/status', 'Painel::saveStatus');

// salvar a atualização da página
$routes->post('post/update/(:num)', 'Painel::pageUpdate/$1');

// deletar publicação
$routes->get('delete_publicacao/(:num)', 'Painel::deletePost/$1');

// publicar ou esconder
$routes->post('publicar', 'Painel::publish');

// busca na publicação
$routes->post('publicacoes/busca', 'Painel::searchPublication');
$routes->post('alterar_radio', 'Painel::radioUpdate');
$routes->get('publicacoes/busca', 'Painel::searchPublication');

// carrossel de Imagens
$routes->post('add_carousel', 'Painel::addCarousel');
$routes->post('delete_carousel/(:num)', 'Painel::deleteCarousel/$1');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
