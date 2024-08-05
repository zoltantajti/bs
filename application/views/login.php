<!DOCTYPE html>
<html lang="hu" class="h-100" data-bs-theme="light">
<head>
    <base href="<?=base_url()?>" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">    
    <meta name="robots" content="noindex, nofollow" />
    <meta name="author" content="Tajti Zoltán | tajtizoltan.hu">    
    <link rel="icon" type="image/png" href="./assets/images/favicon.png" />
    <link rel="stylesheet" media="screen" type="text/css" href="./assets/css/bootstrap.min.css" />
    <link rel="stylesheet" media="screen" type="text/css" href="./assets/css/fa.all.min.pro.css" />
    <link rel="stylesheet" media="screen" type="text/css" href="./assets/css/login.css" />
    
    <title>Viki Oldala</title>
</head>
<body class="">
<div class="container">
    <div class="row px-3">
        <div class="col-lg-10 col-xl-9 card flex-row mx-auto px-0">
            <div class="img-left d-none d-md-flex"></div>
            <div class="card-body">
                <h4 class="title text-center mt-4">
                    Belépés
                </h4>
                <form class="form-box px-3" method="POST" action="" autocomplete="off">
                    <?=$this->Msg->get()?>
                    <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                    <div class="form-input mb-3">
                        <span><i class="fa fa-user"></i></span>
                        <input type="text" name="username" id="username" placeholder="Felhasználónév" tabindex="10">
                        <?=form_error('username','<div class="error">','</div>')?>
                    </div>
                    <div class="form-input mb-3">
                        <span><i class="fa fa-key"></i></span>
                        <input type="password" name="password" id="password" placeholder="Jelszó">
                        <?=form_error('password','<div class="error">','</div>')?>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-block text-uppercase">
                            BELÉPÉS
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="./assets/js/jquery.min.js"></script>
</body>
</html>