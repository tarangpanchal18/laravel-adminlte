<?php

return [
    //Texual changes
    'default_password_regx' => '/^.*(?=.{6,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%@]).*$/',
    'default_password_help' => 'Please create a strong password with combination of 1 uppercase, lowercase and special character.',
    'default_edit_txt' => 'Edit data',
    'default_delete_txt' => 'Delete Data',
    'default_status_change_txt' => 'Click to change status',

    'default_data_insert_msg' => 'Data inserted successfully',
    'default_data_update_msg' => 'Data updated successfully',
    'default_data_deleted_msg' => 'Data deleted successfully',
    'default_data_failed_msg' => 'Whoops! Something went wrong. Log has been reported.',

    'default_datetime_format' => 'd-m-Y H:iA',
    'default_date' => 'd-m-Y',

    //features changes
    'feature_permission' => true,

];
