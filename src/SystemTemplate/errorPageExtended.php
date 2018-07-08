<?php

/** @var \Throwable $exception */

use Colibri\WebApp\Util\Profiler;

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Exception: [<?php echo get_class($exception); ?>]</title>
    <link
            href='https://fonts.googleapis.com/css?family=Exo+2:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&subset=cyrillic,latin-ext'
            rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?= $url->staticPath('css/site.min.css'); ?>">
</head>
<body>

<main>

    <article class="body">

        <div class="container">
            <div class="caption">
                Exception: [<?php echo get_class($exception); ?>]</b>
            </div>
            <div class="content">
                <h3><?php echo $exception->getMessage(); ?></h3>
                <pre><?php echo $exception->getTraceAsString(); ?></pre>
            </div>
            <div class="footer">
                <div class="button-group">
                    <a class="button notice" href="<?php echo $url->path('/'); ?>">Home Page</a>
                </div>
            </div>
        </div>

    </article>
</main>

<footer class="footer-site">
    &copy; BEON.in.UA&nbsp;2016-<?= date('Y'); ?>&nbsp;&nbsp;&nbsp;&nbsp;<span
            class="button size-small info"><?php echo Profiler::timeSpend(); ?>s</span>
    &nbsp;<span class="button size-small warning"><?php echo Profiler::memoryUsage(); ?></span>
</footer>

</body>
</html>