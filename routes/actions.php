<?php

// Home
Route::redirect('/', '/dashboard');
Route::get('/dashboard', Dashboard\Index::class)->middleware(['auth', 'oauth'])->name('dashboard');

// Admin
Route::group(['middleware' => ['auth', 'admin'], 'as' => 'admin.', 'prefix' => 'admin'], function ($router) {
    $router->get('/', Admin\Index::class)->name('index');
});

// Outlook Authentication & Authorization
Route::get('outlook/signin', Auth\Outlook\SignIn::class)->middleware(['auth'])->name('outlook.signin');
Route::get('/authorize', Auth\Outlook\GetToken::class)->middleware(['auth'])->name('outlook.authorize');

// Outlook
Route::post('outlook/sync', Outlook\SyncEmail::class)->middleware(['auth', 'oauth'])->name('outlook.sync');

// Authentication and Registration
// Auth - Login
Route::group(['middleware' => ['guest'], 'as' => 'login.', 'prefix' => 'login'], function ($router) {
    $router->get('/', Auth\Login\ShowForm::class)->name('form');
    $router->post('/', Auth\Login\ProcessLogin::class)->name('attempt');
});

Route::post('/logout', Auth\Logout\ProcessLogout::class)->middleware(['auth'])->name('logout');

// Auth - Register
Route::group(['middleware' => ['guest'], 'as' => 'register.', 'prefix' => 'register'], function ($router) {
    $router->get('/', Auth\Register\ShowForm::class)->name('form');
    $router->post('/', Auth\Register\ProcessRegistration::class)->name('attempt');
});

// Password Reset
Route::group(['middleware' => ['guest'], 'as' => 'password.', 'prefix' => 'password'], function ($router) {
    $router->get('/reset', Auth\PasswordResetRequest\ShowForm::class)->name('request.form');
    $router->post('/email', Auth\PasswordResetRequest\SendEmail::class)->name('request.email');
    $router->get('/reset/{token}', Auth\PasswordReset\ShowForm::class)->name('reset');
    $router->post('/reset', Auth\PasswordReset\UpdatePassword::class)->name('update');
});

/*
 * Email Verification
 *
 * Middleware is defined inside the constructor of each Action.
 * ['auth', 'signed', 'throttle']
 */
Route::group(['as' => 'verification.', 'prefix' => 'email'], function ($router) {
    $router->get('/verify', Auth\EmailVerification\ShowVerification::class)->name('notice');
    $router->get('/verify/{id}', Auth\EmailVerification\Verify::class)->name('verify');
    $router->get('/resend ', Auth\EmailVerification\ResendVerify::class)->name('resend');
});

// Users
Route::group(['middleware' => ['auth'], 'as' => 'users.', 'prefix' => 'users'], function ($router) {
    $router->get('/', User\ListUsers::class)->name('list');
    $router->get('/create', User\CreateUser::class)->name('create');
    $router->post('/', User\StoreUser::class)->name('store');
    $router->delete('/{user}', User\DeleteUser::class)->name('destroy');
    $router->get('/{user}/edit', User\EditUser::class)->name('edit');
    $router->put('/{user}', User\UpdateUser::class)->name('update');
    $router->put('/{user}/restore', User\RestoreUser::class)->name('restore');
});

// Tasks
Route::group(['middleware' => ['auth'], 'as' => 'tasks.', 'prefix' => 'tasks'], function ($router) {
    $router->get('/', Task\ListTasks::class)->name('list');
    $router->post('/process', Task\ProcessTasks::class)->middleware(['oauth'])->name('process');
    $router->get('/create', Task\CreateTask::class)->name('create');
    $router->post('/', Task\StoreTask::class)->name('store');
    $router->delete('/{task}', Task\DeleteTask::class)->name('destroy');
    $router->get('/{task}/edit', Task\EditTask::class)->name('edit');
    $router->put('/{task}', Task\UpdateTask::class)->name('update');
    $router->put('/{task}/restore', Task\RestoreTask::class)->name('restore');
    $router->post('/{task}/email', Task\EmailTask::class)->middleware(['auth', 'admin'])->name('email');
});

// Emails
Route::group(['middleware' => ['auth'], 'as' => 'emails.', 'prefix' => 'emails'], function ($router) {
    $router->get('/', Email\ListEmails::class)->name('list');
    $router->delete('/', Email\DeleteAllEmail::class)->name('destroy.all');
    $router->delete('/{email}', Email\DeleteEmail::class)->name('destroy');
    $router->get('/{email}', Email\ShowEmail::class)->name('show');
    $router->put('/{email}/restore', Email\RestoreEmail::class)->name('restore');
});

// Categories
Route::group(['middleware' => ['auth', 'admin'], 'as' => 'categories.', 'prefix' => 'categories'], function ($router) {
    $router->get('/', Category\ListCategories::class)->name('list');
    $router->delete('/{category}', Category\DeleteCategory::class)->name('destroy');
    $router->get('/{category}', Category\ShowCategory::class)->name('show');
    $router->put('/{category}', Category\UpdateCategory::class)->name('update');
    $router->post('/', Category\StoreCategory::class)->name('store');
    $router->put('/{category}/restore', Category\RestoreCategory::class)->name('restore');
});

// Category Definitions
Route::group(['middleware' => ['auth', 'admin'], 'as' => 'definitions.', 'prefix' => 'definitions'], function ($router) {
    $router->post('/{category}', CategoryDefinition\StoreCategoryDefinition::class)->name('store');
    $router->put('/{definition}', CategoryDefinition\UpdateCategoryDefinition::class)->name('update');
    $router->delete('/{definition}', CategoryDefinition\DeleteCategoryDefinition::class)->name('delete');
});

// PDF
Route::group(['middleware' => ['auth'], 'as' => 'tasks.', 'prefix' => 'tasks'], function ($router) {
    $router->get('/pdf/{type?}', Pdf\ShowTaskListPdf::class);
    $router->get('/{task}/pdf', Pdf\ShowTaskPdf::class)->name('pdf');
});
