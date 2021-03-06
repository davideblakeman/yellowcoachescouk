<?php

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
                    ORDER BY location ASC
                "
            );

            return $result;
        }

        function addLocation( $location )
        {
            global $wpdb;
            $wpdb->show_errors();
            $outcome = 'success';

            // UPDATE question text
            $wpdb->insert( 
                $wpdb->prefix . 'yellowcoachescouk_locations', 
                array(  
                    'location' => $location
                ), 
                array( 
                    '%s'
                ) 
            );

            if ( $wpdb->last_error !== '' )
            {
                // $str   = htmlspecialchars( $wpdb->last_result, ENT_QUOTES );
                // $query = htmlspecialchars( $wpdb->last_query, ENT_QUOTES );
        
                // print "<div id='error'>
                // <p class='wpdberror'><strong>WordPress database error:</strong> [$str]<br />
                // <code>$query</code></p>
                // </div>";
                $outcome = 'fail';
                return $outcome;
            }
            else
            {
                return $outcome;
            }
        }

        public function editLocation( $locationText, $lid )
        {
            global $wpdb;
            $wpdb->show_errors();
            $outcome = 'success';
            
            $wpdb->query(
                $wpdb->prepare( 
                    "
                    UPDATE " . $wpdb->prefix . "yellowcoachescouk_locations
                    SET location = %s
                    WHERE lid = %d
                    ",
                    $locationText,
                    $lid
                )
            );

            if ( $wpdb->last_error !== '' )
            {
                $outcome = 'fail';
                return $outcome;
            }
            else
            {
                return $outcome;
            }
        }

        public function getWCPIDsLinkedToLocation( $lid )
        {
            global $wpdb;

            $result = $wpdb->get_results( $wpdb->prepare( 
                "
                    SELECT wcpid
                    FROM " . $wpdb->prefix . "yellowcoachescouk_quotes
                    WHERE origin = %s OR destination = %s
                ", 
                array(
                    $lid,
                    $lid
                )
            ), OBJECT_K );

            // print_r($result);
            // exit;

            return $result;
        }

        public function getPostContentByWCPID( $wcpid )
        {
            global $wpdb;
            $wpdb->show_errors();
            $outcome = 'success';

            $result = $wpdb->get_results( $wpdb->prepare( 
                "
                    SELECT
                        ID,
                        post_content,
                        post_title,
                        post_excerpt,
                        post_name
                    FROM " . $wpdb->posts . "
                    WHERE ID = %s
                ", 
                array(
                    $wcpid
                )
            ), OBJECT );

            return $result;
        }

        public function editWCProductPost( $wcSave )
        {
            global $wpdb;
            $wpdb->show_errors();
            $outcome = 'success';

            

            $wpdb->query(
                $wpdb->prepare( 
                    "
                    UPDATE $wpdb->posts
                    SET
                        post_content = %s,
                        post_title = %s,
                        post_excerpt = %s,
                        post_name = %s
                    WHERE ID = %d
                    ",
                    $wcSave->content,
                    $wcSave->title,
                    $wcSave->excerpt,
                    $wcSave->name,
                    $wcSave->wcpid
                )
            );
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