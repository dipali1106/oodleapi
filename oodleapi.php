<?php
/*
    Plugin Name: oodlesapi
    Plugin URI: 
    Author: dipali
    Version: 1.0
    Author URI: http://xyz.com
    text-domain:rest
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit;// Exit if accessed directly 
}


add_shortcode('external_data','callback_function_one');
function callback_function_one($atts){
    $post_per_page = 10;
$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
$offset = ( $page * $post_per_page ) - $post_per_page;
    $defaults=[
        'title'=>'data',
        'post_per_page' => 10];
    $atts=shortcode_atts($defaults,
        $atts,
        'external_data');


    $url='https://jsonplaceholder.typicode.com/comments';  //?filter[posts_per_page]=100
    $arguments=array(
        'method'=> 'GET',
    );
    $response=wp_remote_get($url,$arguments);

    if( is_wp_error( $response )  ){
        $error_msg=$response->get_error_message();
        echo "something went wrong:$error_msg";
    }

    $results=json_decode(wp_remote_retrieve_body($response));
    //var_dump($results);
    $total=count($results);


    $html='<table>';
    $html .='<tr>';
    $html .='<td width="100" >user_id</td>';
    $html .='<td width="100" >id</td>';
    $html .='<td width="100" >name</td>';
    $html .='<td width="200" >email</td>';
    $html .='<td width="400" >body</td>';
    $html .='</tr>';
    foreach($results as $result){
         $html .='<tr>';
    $html .='<td width="100" >'. $result->postId.'</td>';
    $html .='<td width="100" >'. $result->id.'</td>';
    $html .='<td width="100" >'. $result->name.'</td>';
    $html .='<td width="100" >'. $result->email.'</td>';
     $html .='<td width="400" >'. $result->body.'</td>';

    $html .='</tr>';

    }
/*   
echo '<div class="pagination">';
echo paginate_links( array(
'base' => add_query_arg( 'cpage', '%#%' ),
'format' => '',
'prev_text' => __('&laquo;'),
'next_text' => __('&raquo;'),
'total' => ceil($total / $post_per_page),
'current' => $page,
'type' => 'list'
));
echo '</div>';*/

    $html.='</table>';


    return $html;


    
    }

?>
      
