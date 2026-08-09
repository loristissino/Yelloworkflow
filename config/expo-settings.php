<?php

return [
    'account'=>function($row) {
        if ($row['payment_method']=='Cash'
        &&
        (
            $row['account_id']==12
            ||
            $row['parent_account_id']==12
        ))
            return 3; // Crediti / debiti vs Sede nazionale
        return false;
    },
];
