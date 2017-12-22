<!DOCTYPE html>
<html>
    <head>
        <title>Omua Framework</title>
        <meta charset="utf-8">
        <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>
         <link href='https://fonts.googleapis.com/css?family=Tinos' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="assets/css/welcome.css">
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Omua Framework</div>
                <div class="subtitle">Basic MVC , CRUD && Session</div>
                <div class="subtitle2"><?php echo  $message = (isset($_content->message)) ? $_content->message : "" ; ?></div>
                <div><a href="?admin/dashboard/login">Control de Session</a> | <a href="?home/blog/index">Documentación</a></div>
            </div>
        </div>
    </body>
</html>