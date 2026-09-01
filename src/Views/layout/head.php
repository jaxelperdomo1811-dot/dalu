<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($tituloPagina) ? htmlspecialchars($tituloPagina) : 'Dalu Boutique'; ?></title>
    
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link rel="icon" href="assets/img/dalulisto.png">
    
    <link rel="stylesheet" href="assets/js/libs/introjs/introjs.css">
    <link rel="stylesheet" href="assets/js/libs/introjs/themes/introjs-modern.css">

    
    <?php if (isset($extraCss) && is_array($extraCss)): ?>
        <?php foreach ($extraCss as $css): ?>
            <link rel="stylesheet" href="<?php echo htmlspecialchars($css); ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <script src="assets/js/libs/jquery-3.7.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/js.js" defer></script>
    
    <script src="assets/js/libs/introjs/intro.js"></script>

    <?php if (isset($extraJs) && is_array($extraJs)): ?>
        <?php foreach ($extraJs as $js): ?>
            <?php if(is_string($js)): ?>
                <script src="<?php echo htmlspecialchars($js); ?>" defer></script>
            <?php else: ?>
                <script src="<?php echo htmlspecialchars($js['src']); ?>" <?php echo isset($js['defer']) && $js['defer'] ? 'defer' : ''; ?>></script>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</head>
