<?php
function base_url() {
  $protocol = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https": "http";
  return $protocol.'://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>404 | Partnership Management system</title>
    <!-- Favicon-->
    <link rel="icon" href="<?php echo base_url(); ?>css/favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url(); ?>/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?php echo base_url(); ?>/css/waves.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="<?php echo base_url(); ?>/css/style.css" rel="stylesheet">
</head>

<body class="four-zero-four">
     <!-- 404 Area -->
    <section class="not-found-wrapper">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="error-img">
                        <img src="img/404-error.png" alt="">
                    </div>
                    <div class="error-text">
                        <h4>Oops!!! We Can’t Find the Page You Are Looking For.</h4>
                    </div>
                    <div class="error-btn">
                         <a href="<?php echo base_url(); ?>/index.php" class="btn btn-default btn-lg waves-effect">GO TO HOMEPAGE</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /404 Area -->

</body>

</html>
