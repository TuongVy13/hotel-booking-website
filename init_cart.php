<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; 
}
if (!isset($_SESSION['search_dates'])) {
    $_SESSION['search_dates'] = ['cin' => '', 'cout' => ''];
}
?>