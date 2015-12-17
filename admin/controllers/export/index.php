<?php


switch($_GET['action']){
    case "export":
        require "export.php";
    break;
    default:
        require "index.php";
    break;
}