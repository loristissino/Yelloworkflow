<?php

    return [
		'attachments' => [
			'class' => nemmo\attachments\Module::className(),
			'tempPath' => '@app/uploads/temp',
			'storePath' => '@app/uploads/store',
			'rules' => [ // Rules according to the FileValidator
				'maxFiles' => 10, // Allow to upload maximum 3 files, default to 3
				'mimeTypes' => [
                    'image/png',
                    'image/jpeg',
                    'application/pdf',
                    'application/vnd.oasis.opendocument.spreadsheet',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-excel',
                    ], // Only png and jpegimages, pdf documents and spreadsheets
				'maxSize' => 30 * 1024 * 1024 // 1 MB
			],  
			'tableName' => '{{attachments}}' // Optional, default to 'attach_file'
		]
	];
    
