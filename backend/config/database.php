<?php
/**
 * Database Configuration
 * University of Sharjah Faculty Recruitment System
 * 
 * Update these credentials to match your XAMPP MySQL setup
 */

return [
    'host' => 'localhost',
    'dbname' => 'uos_recruitment',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];

