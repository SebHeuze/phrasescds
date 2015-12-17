<?php


switch($_GET['action']){
    case "export":
        require "export.php";
    break;
    default:
        require "settings.php";
    break;
}