<?php

require_once __DIR__ . '/log.php';
require_once __DIR__ . '/parsers.php';
require_once __DIR__ . '/UrlMapper.php';

// Base Item
require_once __DIR__ . '/Models/BaseModel.php';

// User Model
require_once __DIR__ . '/Models/UserModel.php';

// Terms
require_once __DIR__ . '/Models/Terms/TermModel.php';
require_once __DIR__ . '/Models/Terms/PostTagModel.php';
require_once __DIR__ . '/Models/Terms/CategoryModel.php';

// Post Types
require_once __DIR__ . '/Models/PostTypes/TypeModel.php';
require_once __DIR__ . '/Models/PostTypes/AttachmentModel.php';
require_once __DIR__ . '/Models/PostTypes/PostModel.php';

// Importer
require_once __DIR__ . '/class-wp-cli-import.php';
