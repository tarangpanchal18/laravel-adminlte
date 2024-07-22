<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('admin.dashboard'));
});

Breadcrumbs::for('profile', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Profile', route('admin.profile'));
});

Breadcrumbs::for('users_list', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Users', route('admin.users.index'));
});
Breadcrumbs::for('users_alter', function (BreadcrumbTrail $trail, $actionData) {
    $trail->parent('users_list');
    $trail->push($actionData, route('admin.users.create'));
});

Breadcrumbs::for('category_list', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Category', route('admin.category.index'));
});
Breadcrumbs::for('category_alter', function (BreadcrumbTrail $trail, $actionData) {
    $trail->parent('category_list');
    $trail->push($actionData, route('admin.category.create'));
});

Breadcrumbs::for('banner_list', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Banner', route('admin.banner.index'));
});
Breadcrumbs::for('banner_alter', function (BreadcrumbTrail $trail, $actionData) {
    $trail->parent('banner_list');
    $trail->push($actionData, route('admin.banner.create'));
});


Breadcrumbs::for('page_list', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Pages', route('admin.pages.index'));
});
Breadcrumbs::for('page_alter', function (BreadcrumbTrail $trail, $actionData) {
    $trail->parent('page_list');
    $trail->push($actionData, route('admin.pages.create'));
});

