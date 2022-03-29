<?php

use DFF\Options\API_Settings;
use DFF\Options\Archive_Settings;
use DFF\Options\Global_Options;
use DFF\Options\FutureId_Settings;

require __DIR__ . '/class-global-options.php';
require __DIR__ . '/class-api-settings.php';
require __DIR__ . '/class-archive-settings.php';
require __DIR__ . '/class-future-id-settings.php';

new Global_Options();
new API_Settings();
new Archive_Settings();
new FutureId_Settings();
