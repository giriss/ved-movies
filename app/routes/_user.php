<?php

app()->group('/user', function () {
    app()->post('/login', 'UsersController@login');
    app()->post('/register', 'UsersController@register');
    app()->get('/monitor', 'UsersController@monitor');
});

app()->group('/movie', function () {
    app()->get('/search', 'MoviesController@search');
    app()->get('/searchByYear', 'MoviesController@searchByYear');
});
