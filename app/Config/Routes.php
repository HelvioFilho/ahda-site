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
$routes->get('adm/recuperar_senha/(:any)/(:any)', 'Admin::recuperar_senha/$1/$2');
$routes->post('adm/recuperar_senha/(:any)', 'Admin::recuperar_senha/$1');
$routes->post('adm/recuperar', 'Admin::recuperar_email');

// Rotas painel
$routes->get('logoff', 'Painel::logoff');
$routes->get('home', 'Painel::home');

// Minha conta
$routes->get('minha_conta', 'Painel::myAccount');
$routes->post('minha_conta/atualizar/contato', 'Painel::updateAccount');
$routes->post('minha_conta/atualizar_img', 'Painel::updateImage');

// usuários
$routes->get('usuarios', 'Painel::usuarios');
$routes->post('user/add', 'Painel::add_user');
$routes->get('delete_user/(:num)', 'Painel::del_user/$1');
$routes->post('desabilitar_user', 'Painel::disabled');
$routes->post('alterar_acesso', 'Painel::updated_access');

//mensagens
$routes->get('mensagens', 'Painel::message');
$routes->get('mensagens/(:num)', 'Painel::message/$1');
$routes->get('mensagens/novo', 'Painel::newMessage');
$routes->get('mensagens/novo/(:num)', 'Painel::newMessage/$1');
$routes->get('delete_mensagem/(:num)', 'Painel::deleteMessage/$1');
$routes->get('marcar_novo', 'Painel::markMessage');
$routes->get('abrir_mensagem', 'Painel::OpenMessage');


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
