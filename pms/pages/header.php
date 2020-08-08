<?php

require_once('functions/CheckerFn.php');
$base_url = Db::$url;
extract($_SESSION);

$path = pathinfo($_SERVER['PHP_SELF']);
$self = $path['filename'];
$c = new CheckerFn();
$c->loginCheck();

function active($page) {
    $path = pathinfo($_SERVER['PHP_SELF']);
    $self = $path['filename'];
    echo $self === $page ? 'class="active"':'';
}
function href($page) {
    echo Db::$url.$page;
}?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title>Partnership Management system</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?php echo $base_url; ?>css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?php echo $base_url; ?>css/waves.css" rel="stylesheet" />

    <!-- Light Gallery Plugin Css -->
    <link href="<?php echo $base_url; ?>css/lightgallery.css" rel="stylesheet">

    <!-- Animation Css -->
    <link href="<?php echo $base_url; ?>css/animate.css" rel="stylesheet" />

    <!-- Morris Chart Css-->
    <link href="<?php echo $base_url; ?>css/morris.css" rel="stylesheet" />
    <link href="<?php echo $base_url; ?>css/dataTables.bootstrap.css" rel="stylesheet" />
    <link href="<?php echo $base_url; ?>css/sweetalert.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="<?php echo $base_url; ?>css/style.css" rel="stylesheet">
    <link href="<?php echo $base_url; ?>css/datepicker.css" rel="stylesheet" />

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?php echo $base_url; ?>css/all-themes.css" rel="stylesheet" />
    <link href="<?php echo $base_url; ?>css/custom-style.css" rel="stylesheet" />

    <!-- Jquery Core Js -->
    <script src="<?php echo $base_url; ?>js/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo $base_url; ?>js/bootstrap.js"></script>

</head>
<body class="theme-red">

    <!--*PRELOADING*------->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div><!-- End -->


    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <div class="overlay"></div>
    <nav class="navbar bg-blue-grey">
        <div class="container-fluid" style="float:left">
            <div class="navbar-header">
                <input type="hidden" id="base_url" data-base_url="<?php echo $base_url ?>">
                <input type="hidden" id="currency" data-currency="<?php echo $currency ?>">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand headerLogo" href="<?php href('index') ?>"><img src="<?php href($logo) ?>" alt=""></a>
                <!-- <a class="navbar-brand headerLogo" href="<?php echo $base_url; ?>index.php"><?php echo $company; ?></a> -->
            </div>
        </div>
        <div class="btn-group user-dropdown">
            <button type="button" class="dropdown-toggle" data-toggle="dropdown">
                <img src="<?php echo $base_url . file_exists($userinfo['photo']) ? $userinfo['photo']:'img/user.png' ?>" alt="">
            </button>
            <ul class="dropdown-menu">
                <?php
                    if(isset($userinfo)) {
                        $id = $userinfo['id'];
                        $key = $_SESSION['sec_a'];
                        $key_v = md5(rand(). $id.time()).$id.rand(1293, 3000);
                    }
                ?>
                <li><a href="<?php echo $base_url.'userProfile?'.$key.'=' . $key_v; ?>" class=" waves-effect waves-block">My profile</a></li>
                <li><a href="<?php echo $base_url.'userRegistration?'.$key.'=' . $key_v; ?>" class=" waves-effect waves-block">Update profile</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="<?php echo $base_url?>logOut" class=" waves-effect waves-block">Log Out</a></li>
            </ul>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- Menu -->

            <div class="menu">

                <ul class="list">

                    <li <?php active('index') ?>>
                        <a href="<?php href('index') ?>">
                            <i class="material-icons">home</i><span>Home</span>
                        </a>
                    </li>

                    <?php if(isset($userinfo) && $userinfo['userRoll'] === 'Partner') { ?>
                    <li <?php active('my_account_details') ?>>
                        <a href="<?php href('my_account_details') ?>">
                            <img class="currency-img" src="<?php echo $base_url ?>img/money-icon.jpg" alt="">
                            <span>My Account</span>
                        </a>
                    </li>
                    <?php }?>

                    <li <?php echo in_array($self, ['earning_list', 'earnings_manage', 'earning_details']) ? 'class="active"':''?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">donut_small</i><span>Earnings</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php active('earnings_manage') ?>>
                                <a href="<?php href('earnings_manage') ?>">
                                    <span><?php echo isset($_GET) && !empty($_GET) && $self === 'earnings_manage' ? 'Update Earning': 'Add Earning' ?></span>
                                </a>
                            </li>
                            <li <?php active('earning_list') ?>>
                                <a href="<?php href('earning_list') ?>">
                                    <span>Earning details</span>
                                </a>
                            </li>
                            <?php if(isset($userinfo) && $userinfo['userRoll'] === 'Partner') { ?>
                            <li <?php active('earning_details') ?>>
                                <a href="<?php href('earning_details') ?>">
                                    <span>User Wise Earning Details</span>
                                </a>
                            </li>
                            <?php }?>
                        </ul>

                    <li <?php echo in_array($self, ['expenses_manage', 'expenses_list', 'expensesDetails']) ? 'class="active"':'' ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">donut_large</i>
                            <span>Expenses</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php active('expenses_manage')?>>
                                <a href="<?php href('expenses_manage')?>">
                                    <span><?php echo isset($_GET) && !empty($_GET) && $self === 'expenses_manage' ? 'expense Edit': 'Add expense' ?></span>
                                </a>
                            </li>
                            <li <?php active('expenses_list')?>>
                                <a href="<?php href('expenses_list')?>">
                                    <span>Expense details</span>
                                </a>
                            </li>
                            <?php if(isset($userinfo) && $userinfo['userRoll'] === 'Partner') { ?>
                            <li <?php active('expensesDetails')?>>
                                <a href="<?php href('expensesDetails')?>">
                                    <span>User Wise Ex. Details</span>
                                </a>
                            </li>
                            <?php }?>
                        </ul>
                    </li>
                        <?php if(isset($userinfo) && $userinfo['userRoll'] === 'Partner') { ?>
                        <li <?php echo in_array($self, ['userRegistration', 'users']) ? 'class="active"':'' ?>>
                            <a href="javascript:void(0);" class="menu-toggle">
                                <i class="material-icons">people</i>
                                <span>User Management</span>
                            </a>
                            <ul class="ml-menu">
                                <li <?php active('userRegistration')?>>
                                    <a href="<?php href('userRegistration')?>">
                                        <span><?php echo isset($_GET) && !empty($_GET) && $self === 'userRegistration' ? 'Edit user': 'Add user' ?></span>
                                    </a>
                                </li>
                                <li <?php active('users')?>>
                                    <a href="<?php href('users')?>">
                                        <span>All Users</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </li>
                    <li <?php active('manageSettings')?>>
                        <a href="<?php href('manageSettings')?>">
                            <i class="material-icons">settings</i>
                            <span>Settings</span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; <?php echo date('Y'); ?> <a href="https://shahadat-khan.com" target="_blank">shahadat-khan.com</a>
                </div>
            </div>
            <!-- #Footer -->
        </aside>
    </section>
