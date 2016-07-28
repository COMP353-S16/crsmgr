<?php
return array (

    'appname' => 'CRSMGR - FSS',
    'uploads' => array(
        'upload_dir' => 'uploads/',
        'maxupload' => $max_upload = min((int)ini_get('post_max_size'), (int)(ini_get('upload_max_filesize'))) * 1024 * 1024,
        'storageDB' => true,  // true for DB, false for Filesystem
        'allowed_files' => array(
            'pdf',
            'txt',
            'doc',
            'docx',
            'xls',
            'jpg',
            'png')
    ),
    'timezone' => 'America/Montreal'


);

?>