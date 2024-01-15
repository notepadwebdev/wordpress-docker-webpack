<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title><?php wp_title(); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  <div class="body-wrap">

  <header id="body-wrap" class="site-header">
    <div class="container">
      
      <div class="site-header__logo">
        <?php
          if ( function_exists( 'the_custom_logo' ) ) {
            the_custom_logo();
          }
        ?>
      </div>
    
    </div>
  </header>
  
  <main id="site-content" role="main">
    <div id="scroll-pixel" class="scroll-pixel"></div>
