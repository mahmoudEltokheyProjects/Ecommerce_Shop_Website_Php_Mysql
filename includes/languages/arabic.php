<?php

    function langFunc($phrase)
    {
        static $lang = array(
            'MESSAGE' => 'مرحباً' ,
            'ADMIN' => 'المدير'
        );
        return $lang[$phrase];
    }   

?>