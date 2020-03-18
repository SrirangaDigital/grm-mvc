<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Google Analytics
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-117840279-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-117840279-1');
    </script>


    <!-- Basic Page Needs
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <meta charset="utf-8">
    <title><?php if($pageTitle) echo $pageTitle . ' | '; ?>Grantharathnamaala</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Mobile Specific Metas
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- FONT
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display|Raleway:200,300,400|Roboto:300,400&amp;subset=latin-ext" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Arima+Madurai:400,700&amp;subset=tamil" rel="stylesheet">

    <!-- Javascript calls
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script type="text/javascript" src="<?=PUBLIC_URL?>js/jquery.viewport.js"></script>
    <script type="text/javascript" src="<?=PUBLIC_URL?>js/viewer.js"></script>
    <script type="text/javascript" src="<?=PUBLIC_URL?>js/common.js?version=2.3.1"></script>
    
    <!-- CSS
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link rel="stylesheet" href="<?=PUBLIC_URL?>css/fonts.css?version=1.1">
    <link rel="stylesheet" href="<?=PUBLIC_URL?>css/style.css?version=2.3">
    <link rel="stylesheet" href="<?=PUBLIC_URL?>css/navbar.css?version=1.1">
    <link rel="stylesheet" href="<?=PUBLIC_URL?>css/sidebar.css?version=1.1">
    <link rel="stylesheet" href="<?=PUBLIC_URL?>css/carousel.css?version=1.1">
    <link rel="stylesheet" href="<?=PUBLIC_URL?>css/page.css?version=2.6">
    <link rel="stylesheet" href="<?=PUBLIC_URL?>css/archive.css?version=1.1">
    <link rel="stylesheet" href="<?=PUBLIC_URL?>css/general.css?version=1.1">
    <link rel="stylesheet" href="<?=PUBLIC_URL?>css/flat.css?version=1.1">
    <link rel="stylesheet" href="<?=PUBLIC_URL?>css/form.css?version=1.2">
    <link rel="stylesheet" href="<?=PUBLIC_URL?>css/aux.css?version=1.1">
    <link rel="stylesheet" href="<?=PUBLIC_URL?>css/social.css?version=1.1">
    <link rel="stylesheet" href="<?=PUBLIC_URL?>css/viewer.css">

    <!-- Favicon
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="icon" type="image/png" href="<?=PUBLIC_URL?>images/favicon.png">
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body class="home">
    <header id="header">

<!-- Display mast head only for the home page -->
<?php if(preg_match('/flat\/ಮುಖಪುಟ/u', $path)) { ?>
        <!-- mast head -->
        <div id="head" class="mx-0 row justify-content-center">
            <div class="col-md-5 text-center align-self-center">
                <h1 id="logo">
                    <img class="img-circle" src="<?=PUBLIC_URL?>images/logo-circle.png?v-1.0" alt="">
                    <span class="title">ಶ್ರೀ ಜಯಚಾಮರಾಜೇಂದ್ರ<br />ಗ್ರಂಥರತ್ನಮಾಲಾ</span>
                    
                    <span class="tagline"></span>
                </h1>
            </div>
        </div>
<?php } ?>
        <!-- Navigation
        –––––––––––––––––––––––––––––––––––––––––––––––––– -->
        <nav id="mainNavBar" class="navbar navbar-light navbar-expand-lg">
            <div class="container-fluid clear-paddings">
                <a class="navbar-brand" href="<?=BASE_URL?>"><img src="<?=PUBLIC_URL?>images/logo.png" alt="Logo" class="logo"></a>
                <p class="navbar-text" id="navbarText"><small>ಶ್ರೀ ಜಯಚಾಮರಾಜೇಂದ್ರ</small><br />ಗ್ರಂಥರತ್ನಮಾಲಾ</p>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <?=$this->printNavigation($navigation)?>
                </div>
            </div>
        </nav>
        <!-- End Navigation
        –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    </header>
