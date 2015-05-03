<?php

// error_reporting(-1);
session_start();

/**
 * Load autoload file
 */
require '../vendor/autoload.php';

/**
 * Load helpers file
 */
require '../app/helpers.php';

/**
 * Load env varriables
 */
Dotenv::load(base_path());
