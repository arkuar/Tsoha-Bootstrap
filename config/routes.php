<?php

$routes->get('/', function() {
    MovieController::index();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/login', function() {
    AccountController::login();
});

$routes->post('/login', function() {
    AccountController::handle_login();
});

$routes->get('/register', function() {
    AccountController::register();
});

$routes->post('/register', function() {
    AccountController::store();
});

$routes->get('/logout', function() {
    AccountController::logout();
});

$routes->post('/accounts/:id/show', function($id) {
    AccountController::toggle_ban($id);
});

$routes->get('/accounts/:id/show', function($id) {
    AccountController::show($id);
});

$routes->get('/accounts/:id/edit', function($id) {
    AccountController::edit($id);
});

$routes->post('/accounts/:id/edit', function($id) {
    AccountController::update($id);
});

$routes->post('/accounts/:id/destroy', function($id) {
    AccountController::destroy($id);
});

$routes->get('/movies', function() {
    MovieController::index();
});

$routes->post('/movies', function() {
    MovieController::store();
});

$routes->get('/movies/new', function() {
    MovieController::create();
});

$routes->get('/movies/:id', function($id) {
    MovieController::show($id);
});

$routes->post('/movies/:id/new', function() {
    MessageController::store();
});

$routes->get('/movies/:id/new', function($id) {
    MessageController::create($id);
});

$routes->get('/movies/:id/edit', function($id) {
    MovieController::edit($id);
});

$routes->post('/movies/:id/edit', function($id) {
    MovieController::update($id);
});

$routes->post('/movies/:id/destroy', function($id) {
    MovieController::destroy($id);
});

$routes->post('/messages/:id/destroy', function($id) {
    MessageController::destroy($id);
});

$routes->get('/genres', function() {
    GenreController::index();
});

$routes->post('/genres', function() {
    GenreController::store();
});

$routes->get('/genres/new', function() {
    GenreController::create();
});

$routes->get('/genres/:id', function($id) {
    GenreController::show($id);
});

$routes->get('/genres/:id/edit', function($id) {
    GenreController::edit($id);
});

$routes->post('/genres/:id/edit', function($id) {
    GenreController::update($id);
});

$routes->post('/genres/:id/destroy', function($id) {
    GenreController::destroy($id);
});
