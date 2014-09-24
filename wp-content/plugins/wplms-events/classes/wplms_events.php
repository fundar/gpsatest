<?php

/**
 * This Class is an interface to the database for displaying, storing, editing Events
 * WPLMS Events reside in the WP Posts table and follow the WP Post architecture
 * WPLMS Events follow the BP+WP architechture, 
 */

class WPLMS_Events {
    var $id;
    var $date;
    var $query;


    function __construct( $args = array() ) {
        // Set some defaults
        $defaults = array(
            'id'            => 0,
            'date'  => date( 'Y-m-d H:i:s' )
        );

        // Parse the defaults with the arguments passed
        $r = wp_parse_args( $args, $defaults );
        extract( $r );

        if ( $id ) {
            $this->id = $id;
            $this->populate( $this->id );
        } else {
            foreach( $r as $key => $value ) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * populate()
     *
     * This method will populate the object with a row from the database, based on the
     * ID passed to the constructor.
     */
    function populate() {
        global $wpdb, $bp;
        $post = get_post($this->id);
    }


    /**
     * Fire the WP_Query
     *
     * @package BuddyPress_Course_Component
     * @since 1.6
     */
    function get( $args = array() ) {

        // Only run the query once
        if ( empty( $this->query ) ) {

            $defaults = array(
            'id'         => 0,
            'author'  => 0,
            'order'     => 'DESC',
            'orderby'   => '',
            'meta_key'  => '',
            'date'    => date( 'Y-m-d H:i:s' ),
            'post_per_page' => 5
            );

           
            $r = wp_parse_args( $args, $defaults );
            extract( $r );

            if(isset($course) && $course){
                $meta_query[]=array(
                    'key' => 'vibe_event_course',
                    'compare' => '=',
                    'value' => intval($course),
                    'type' => 'DECIMAL'
                );
            }

            if(isset($date) && $date){
                $meta_query['relation'] = '"AND"';
                $meta_query[]=array(
                    'key' => 'vibe_start_date',
                    'compare' => '<=',
                    'value' => $date,
                    'type' => 'DATE'
                );
                $meta_query[]=array(
                    'key' => 'vibe_end_date',
                    'compare' => '>=',
                    'value' => $date,
                    'type' => 'DATE'
                );
            }
            
            $query_args = array(
                'post_status'    => 'publish',
                'post_type'  => 'wplms-event',
                'order' => $order,
                'orderby'=> $orderby,
                'meta_query'     => $meta_query,
                's' => $search_terms,
                'post_per_page' => $post_per_page,
                'paged'      => $paged
            );

            if(isset($from_date) && isset($to_date)){
            $date_query = array(
                            array(
                                'after'     => $from_date,
                                'before'    => $to_date,
                                'inclusive' => true,
                            ),
            );
            $query_args['date_query']=$date_query;
            }

            // Some optional query args
            // Note that some values are cast as arrays. This allows you to query for multiple
            // authors/recipients at a time
            if ( isset($instructor )){
                $query_args['author'] = $instructor;
            }

            if(isset($id)){
                $query_args['p']=$id;
            }

            /*global $wpdb; Actual query
            $eventdaysquery = $wpdb->get_results("SELECT start.post_id as id, start.meta_value as start_date, end.meta_value as end_date
                FROM {$wpdb->postmeta} AS start
                INNER JOIN {$wpdb->postmeta} AS end
                ON start.post_id=end.post_id
                WHERE start.meta_key = 'vibe_start_date'
                AND end.meta_key = 'vibe_end_date'
                AND '$date' BETWEEN start.meta_value AND end.meta_value
                ");
            
            print_r($eventdaysquery); */
            // Run the query, and store as an object property, so we can access from
            // other methods
           
            $this->query = new WP_Query( $query_args );
            
        }
    }

    function have_posts() {

        return $this->query->have_posts();
    }

    function the_post() {
        return $this->query->the_post();
    }

    /**
     * delete()
     *
     * This method will delete the corresponding row for an object from the database.
     */
    function delete() {
        return wp_trash_post( $this->id );
    }

}

?>