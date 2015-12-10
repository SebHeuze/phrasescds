<?php

/* index.html */
class __TwigTemplate_2d456d45bfd71d501c98377624f4436558a0b2c4352ee9d144f41c83ffee4cee extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class=\"no-js lt-ie9 lt-ie8 lt-ie7\" lang=\"en\"> <![endif]-->
<!--[if IE 7]>    <html class=\"no-js lt-ie9 lt-ie8\" lang=\"en\"> <![endif]-->
<!--[if IE 8]>    <html class=\"no-js lt-ie9\" lang=\"en\"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class=\"no-js\" lang=\"en\"> <!--<![endif]-->
<head>
  <meta charset=\"utf-8\">
  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\">
  <meta name=\"description\" content=\"\">
  <meta name=\"viewport\" content=\"width=device-width\">

  <title>Twig Test - ";
        // line 14
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
        echo "</title>
  

  <link rel=\"stylesheet\" href=\"css/style.css\">

  <script src=\"js/libs/modernizr-2.5.3.min.js\"></script>
</head>
<body>
  <!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href=\"http://browsehappy.com/\">Upgrade to a different browser</a> or <a href=\"http://www.google.com/chromeframe/?redirect=true\">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
  <header>

  </header>
  <div role=\"main\">
    ";
        // line 27
        if (twig_test_empty((isset($context["name"]) ? $context["name"] : null))) {
            // line 28
            echo "\t\t";
            $context["name"] = "test";
            // line 29
            echo "\t";
        }
        // line 30
        echo "\t<h1> Welcome ";
        echo twig_escape_filter($this->env, (isset($context["name"]) ? $context["name"] : null), "html", null, true);
        echo "</h1>
  </div>
  <footer>

  </footer>


  <script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js\"></script>
  <script>window.jQuery || document.write('<script src=\"js/libs/jquery-1.7.1.min.js\"><\\/script>')</script>

</body>
</html>";
    }

    public function getTemplateName()
    {
        return "index.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  58 => 30,  55 => 29,  52 => 28,  50 => 27,  34 => 14,  19 => 1,);
    }
}
/* <!doctype html>*/
/* <!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->*/
/* <!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->*/
/* <!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->*/
/* <!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->*/
/* <!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->*/
/* <!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->*/
/* <head>*/
/*   <meta charset="utf-8">*/
/*   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">*/
/*   <meta name="description" content="">*/
/*   <meta name="viewport" content="width=device-width">*/
/* */
/*   <title>Twig Test - {{ title }}</title>*/
/*   */
/* */
/*   <link rel="stylesheet" href="css/style.css">*/
/* */
/*   <script src="js/libs/modernizr-2.5.3.min.js"></script>*/
/* </head>*/
/* <body>*/
/*   <!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->*/
/*   <header>*/
/* */
/*   </header>*/
/*   <div role="main">*/
/*     {% if name is empty %}*/
/* 		{% set name = "test" %}*/
/* 	{% endif %}*/
/* 	<h1> Welcome {{ name }}</h1>*/
/*   </div>*/
/*   <footer>*/
/* */
/*   </footer>*/
/* */
/* */
/*   <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>*/
/*   <script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.1.min.js"><\/script>')</script>*/
/* */
/* </body>*/
/* </html>*/
