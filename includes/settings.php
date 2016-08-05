<?php
return array (

    'appname' => 'CRSMGR - FSS',
    'protocol' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://",
    'uploads' => array(
        'upload_dir' => 'uploads/',
        'maxupload' => $max_upload = min((int)ini_get('post_max_size'), (int)(ini_get('upload_max_filesize'))) * 1024 * 1024,
        'storageDB' => true,  // true for DB, false for Filesystem. However, File system permissions can cause problems!!!
        'allowed_files' => array(
            'pdf',
            'zip',
            'txt',
            'txt',
            'tiff',
            'jpeg',
            'doc',
            'docx',
            'xls',
            'jpg',
            'png')
    ),
    'timezone' => 'America/Montreal',
    'maxGroupQuota' => 2048,  // NEVER REMOVE THIS!
    'archive' => array(
        'dir' => 'archives/'
    )


);

?>