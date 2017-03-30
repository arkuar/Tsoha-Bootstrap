<?php

$routes->get('/', function() {
    HelloWorldController::index();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/login', function() {
    HelloWorldController::login();
});

$routes->get('/movies', function() {
    MovieController::index();
});

$routes->post('/movies', function(){
    MovieController::store(); 
});

$routes->get('/movies/new', function() {
    MovieController::create();
});

$routes->get('/movies/:id', function($id) {
    MovieController::show($id);
});

$routes->get('/movies/1/edit', function() {
    HelloWorldController::movie_edit();
});

$routes->get('/messages/1/edit', function() {
    HelloWorldController::message_edit();
});

$routes->get('/genres', function() {
    HelloWorldController::genre_list();
});

$routes->get('/genres/1', function() {
    HelloWorldController::genre_show();
});

$routes->get('/genres/1/edit', function() {
    HelloWorldController::genre_edit();
});
