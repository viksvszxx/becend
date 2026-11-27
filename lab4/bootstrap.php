<?php
require_once __DIR__ . '/classes/Category.php';
require_once __DIR__ . '/classes/Example.php';
require_once __DIR__ . '/classes/Command.php';
require_once __DIR__ . '/classes/CommandRepository.php';

$repository = new CommandRepository(
    __DIR__ . '/data/categories.csv',
    __DIR__ . '/data/commands.csv',
    __DIR__ . '/data/examples.csv'
);
