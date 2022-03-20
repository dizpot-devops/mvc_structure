<?php
if(isset($viewmodel) && $viewmodel->getMetaKey('reroute')) {

}
?>
<!DOCTYPE html>

<head>
    <base href="../../">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $viewmodel->getMetaKey('page_title'); ?></title>
    <link href="https://fonts.googleapis.com/css?family=Roboto+Mono:400|Roboto:300,400,500,700,900|Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/pages/session/error.min.css">
    <link rel="stylesheet" href="/assets/css/main.bundle.min.css">
</head>

<body>
<div class="page-wrap height-100">
<?php require($view); ?>
</div>
</body>