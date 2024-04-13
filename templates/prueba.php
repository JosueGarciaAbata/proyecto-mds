<!-- Here is everything I need for the pages -->
<!doctype html>
<html lang="<?php echo $template->get('lang'); ?>">

<head>
    <meta charset="utf-8">
    <title>
        <?php echo $template->get('titulo'); ?>
    </title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>

<body>
    <?php
    echo $template->get('descripcion');
    ?>
</body>

</html>