<?php

    function langFunc($phrase)
    {
        static  $langs = array(
            // Navbar Links
            'HOME_ADMIN' => 'Home',
            'CATEGORIES' => 'Categories',
            'ITEMS'      => 'Items',
            'MEMBERS'    => 'Members',
            'STATISTICS' => 'Statistics',
            'COMMENTS'   => 'Comments',
            'LOGS'       => 'Logs',
        );
        return $langs[$phrase];
    }    

?>

