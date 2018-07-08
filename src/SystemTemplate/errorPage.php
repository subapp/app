<?php
/** @var \Throwable $exception */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Error Page [<?php echo get_class($exception); ?>]</title>
    <style>
        html {
            font-family: "Courier New";
            background-color: #9acae2;
        }

        .box {
            width: 1200px;
            margin: 200px auto;
            padding: 20px;
            background-color: #ffe1b8;
            border: 1px solid #ffd195;
        }
    </style>
</head>
<body>

<div class="box">
    <h1>[<?php echo get_class($exception); ?>]</h1>
    <h3><?php echo $exception->getMessage(); ?></h3>
    <pre><?php echo $exception->getTraceAsString(); ?></pre>
</div>

</body>
</html>