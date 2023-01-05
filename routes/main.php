<?php 

use core\app;
// ([0-9a-zA-Z-_]+)
// ([0-9a-zA-Z-_]+)
// ([0-9a-zA-Z-_\?\=\&\+]+)
// ([0-9]+)

/*home*/
app::get('/', '/home/index', 'main');
app::get('/home/page/([0-9]+)', '/home/index', 'main');
app::get('/mode/([0-9]+)', '/home/mode/([0-9]+)', 'main');


/*profile*/
app::get('/profile/info', '/profile/ProfileInfo', 'main', ['Auth']);
app::get('/profile/read/([0-9]+)/page/([0-9]+)', '/profile/read/([0-9]+)', 'main', ['Auth']);
app::get('/profile/update', '/profile/update', 'main', ['Auth']);
app::post('/profile/update', '/profile/ProfileUpdate', 'main', ['Auth']);

/*article*/
app::get('/article/page/([0-9]+)', '/article/article', 'main');
app::get('/article/show/([0-9]+)', '/article/show/([0-9]+)', 'main');
app::get('/article/create', '/article/create', 'main', ['Auth']);
app::post('/article/create', '/article/articleCreate', 'main', ['User']);
app::get('/article/update/([0-9]+)', '/article/update/([0-9]+)', 'main', ['Auth']);
app::post('/article/update', '/article/ArticleUpdate', 'main', ['User']);
app::get('/article/delete/([0-9]+)', '/article/articleDelete/([0-9]+)', 'main', ['User']);
app::get('/article/type/([0-9]+)', '/article/ArticleType/([0-9]+)', 'main', ['User']);
app::get('/article/user/([0-9]+)/page/([0-9]+)', '/article/articleByUser/([0-9]+)', 'main');
app::get('/article/search/key/(.*?)', '/article/ArticleSearchEngine', 'main', ['Auth']);
app::get('/article/search/data/([0-9a-zA-Z-_\?\=\&\+]+)/page/([0-9]+)', '/article/ArticleSearch/([0-9a-zA-Z-_\?\=\&\+]+)', 'main');
app::get('/article/read/([0-9]+)/page/([0-9]+)', '/article/ArticleRead/([0-9]+)', 'main');
app::get('/category/article/([0-9]+)/page/([0-9]+)', '/article/ByCategory/([0-9]+)', 'main');

app::get('/article/vote/([0-9a-zA-Z-_]+)/([0-9]+)', '/article/ArticleVote/([0-9a-zA-Z-_]+)/([0-9]+)', 'main', ['Auth']);

/*category*/
app::get('/category/page/([0-9]+)', '/category/category', 'main');

/*comment*/
app::get('/comment/page/([0-9]+)', '/comment/comment', 'main', ['Auth']);
app::get('/comment/own/show/([0-9]+)', '/comment/OwnShow/([0-9]+)', 'main', ['Auth']);
app::get('/comment/user/article/([0-9]+)/page/([0-9]+)', '/comment/CommentByOwnArticle/([0-9]+)', 'main', ['Auth']);

app::post('/comment/create', '/comment/CommentCreate', 'main', ['Auth']);
app::get('/comment/delete/([0-9]+)', '/comment/CommentDelete/([0-9]+)', 'main', ['User']);
app::get('/comment/destroy/([0-9]+)', '/comment/CommentDestroy/([0-9]+)', 'main', ['User']);

/*other*/
app::get('/info/about', '/info/AboutPage', 'main', ['Page']);
app::get('/info/rule/page/([0-9]+)', '/info/RulePage', 'main', ['Page']);
app::get('/info/faq/page/([0-9]+)', '/info/FaqPage', 'main', ['Page']);

/*auth*/
// app::get('/auth', '/auth/AuthPage', ['Authless']);
app::get('/auth', '/auth/AuthPage', 'main', ['Authless']);
app::post('/signup', '/auth/SignUp', 'main', ['Authless']);
app::post('/signin', '/auth/SignIn', 'main', ['Authless']);
app::get('/signout', '/auth/SignOut', 'main', ['Auth']);

/*contact*/
app::get('/contact', '/contact/ContactPage', 'main');
app::post('/contact', '/contact/SendMessage', 'main');

/*error*/
app::get('/404', '/error/PageNotFound', 'main');
app::get('/error/type/([0-9a-zA-Z-_]+)', '/error/errorType/([0-9a-zA-Z-_]+)', 'main');

/*dd*/
app::get('/dd', '/dd/dd', 'main');


