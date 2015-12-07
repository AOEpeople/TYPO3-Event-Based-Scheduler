<?php
use Aoe\EventBasedScheduler\Scheduler\Task;
use Aoe\EventBasedScheduler\Scheduler\FieldProvider;

global $_EXTKEY;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][Task::class] = array(
    'extension' => $_EXTKEY,
    'title' => 'EventBasedScheduler:title',
    'description' => 'EventBasedScheduler:description',
    'additionalFields' => FieldProvider::class
);
