<?php
include __DIR__ . '/../../config.php';
include BASE_PATH . '/helpers/AppManager.php';

$sm = AppManager::getSM();

$userId = $sm->getAttribute("userId");
$username = $sm->getAttribute("username");
$role = $sm->getAttribute("role");

// Extract the last filename from the URL
$currentUrl = $_SERVER['SCRIPT_NAME'];
$currentFilename = basename($currentUrl);  // e.g., "dashboard.php"

?>

<!DOCTYPE html>
<!-- beautify ignore:start -->
<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>E-Library</title>

    <meta name="description" content="" />
    <meta name="domain" content="<?= current_domain() ?>" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= asset('assets/img/favicon/logo.png') ?>" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="<?= asset('assets/vendor/fonts/boxicons.css') ?>" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= asset('assets/vendor/css/core.css') ?>" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?= asset('assets/vendor/css/theme-default.css') ?>" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?= asset('assets/css/demo.css') ?>" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?= asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') ?>" />

    <link rel="stylesheet" href="<?= asset('assets/vendor/libs/apex-charts/apex-charts.css') ?>" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="<?= asset('assets/vendor/js/helpers.js') ?>"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?= asset('assets/js/config.js') ?>"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="index.html" class="app-brand-link">
                        <span class="app-brand-logo demo">

                        </span>
                        <span class="app-brand-text demo menu-text fw-bolder ms-2 text-capitalize text-dark">E-Library</span>
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">

                    <li class="menu-item <?= $currentFilename === "dashboard.php" ? 'active' : '' ?> " >
                        <a href="<?= url('views/admin/dashboard.php') ?>" class="menu-link" >
                            <i class="menu-icon tf-icons bx bx-library btn-outline-dark" ></i>
                            <div data-i18n="appointments" class=" text-dark">Dashboard</div>
                        </a>
                    </li>


                    <?php if ($role == 'admin') : ?>
                        <li class="menu-item <?= $currentFilename === "members.php" ? 'active' : '' ?>">
                            <a href="<?= url('views/admin/members.php') ?>" class="menu-link" >
                                <i class="menu-icon tf-icons bx bx-body btn-outline-dark"></i>
                                <div data-i18n="Analytics" class=" text-dark">Members</div>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="menu-item <?= $currentFilename === "books.php" ? 'active' : '' ?> ">
                        <a href="<?= url('views/admin/books.php') ?>" class="menu-link">
                            <i class="menu-icon tf-icons  bx bx-book btn-outline-dark"></i>
                            <div data-i18n="Analytics" class=" text-dark">Book-collection</div>
                        </a>
                    </li>
                    <?php if (isset($role) ) : ?>
                    
                        <li class="menu-item  <?= $currentFilename === "borrowed_books.php" ? 'active' : '' ?> ">
                            <a href="<?= url('views/admin/borrowed_books.php') ?>" class="menu-link">
                                <i class="menu-icon tf-icons  bx bx-bookmark btn-outline-dark"></i>
                                <div data-i18n="Analytics" class=" text-dark">Borrowed Books</div>
                            </a>
                        </li>
                    <?php endif; ?>
                        
                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav
                    class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">


                        <ul class="navbar-nav flex-row align-items-center ms-auto">


                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="<?= asset('assets/img/avatars/1.png') ?>" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="<?= asset('assets/img/avatars/1.png') ?>" alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block"><?= $username ?></span>
                                                    <small class="text-muted text-capitalize"><?= $role ?></small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>


                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>

                                    <li>
                                    <?php if (isset($role)) : ?>
                                        <a class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">
                                                Logout
                                            </span>
                                            <form id="logout-form" action="<?= url('services/logout.php') ?>" method="POST" class="d-none">

                                            </form>
                                        </a>
                                    <?php else: ?>

                                        <a class="dropdown-item" onclick="event.preventDefault(); document.getElementById('login-form').submit();">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">
                                                Login
                                            </span>
                                            <form id="login-form" action="<?= url('views/auth/login.php') ?>" method="POST" class="d-none">

                                            </form>
                                        </a>
                                    <?php endif; ?>

                                    </li>

                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>

                <!-- / Navbar -->