<?php

// namespace YellowCoachescouk;

if ( !defined( 'ABSPATH' ) ) die();

    class YellowcoachescoukWPDB
    {
        public function getQuote( $origin, $destination )
        {
            global $wpdb;

            $result = $wpdb->get_row( $wpdb->prepare( 
                "
                    SELECT cost, wcpid
                    FROM " . $wpdb->prefix . "yellowcoachescouk_quotes
                    WHERE origin = %s AND destination = %s
                ", 
                array(
                    $origin,
                    $destination
                )
            ));

            return $result;
        }

        public function getAllLocations()
        {
            global $wpdb;

            $result = $wpdb->get_results(
                "
                    SELECT lid, location 
                    FROM " . $wpdb->prefix . "yellowcoachescouk_locations
                "
            );

            return $result;
        }

        public function setupDBSchema()
        {
            global $wpdb;

            $charset_collate = '';
        
            if ( !empty( $wpdb->charset ) )
            {
                $charset_collate = 'DEFAULT CHARACTER SET ' . $wpdb->charset;
            }
                
            if ( !empty( $wpdb->collate ) )
            {
                $charset_collate .= ' COLLATE ' . $wpdb->collate;
            }

            $sql = "
                CREATE TABLE IF NOT EXISTS $wpdb->yellowcoachescouk_quotes (
                    id INT unsigned NOT NULL auto_increment,
                    origin TEXT NOT NULL,
                    destination TEXT NOT NULL,
                    cost DECIMAL NOT NULL,
                    wcpid INT NOT NULL,
                    PRIMARY KEY (id)
                )  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

                CREATE TABLE IF NOT EXISTS $wpdb->yellowcoachescouk_locations (
                    lid INT unsigned NOT NULL auto_increment,
                    location TEXT NOT NULL,
                    PRIMARY KEY (lid)
                )  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
            ";

            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta( $sql );

            add_option( 'yellowcoaches-dbschema-setup', 1 );
        }
    }
?>