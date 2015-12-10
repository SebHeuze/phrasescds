<?php

switch($_GET['action']){
    case "json":
        require "json.php";
    break;
    case "create":
        require "create.php";
    break;
    case "delete":
        require "delete.php";
    break;
    case "edit":
        require "edit.php";
    break;
    case "list":
    default:
        require "list.php";
    break;
}




