<?php
/**
 * Code to Create Custom WordPress login form without plugin 
 * @author Joe Njenga 
 * @ gist  - 
 */ 


// Step 1: Create shortcode
function njengah_add_login_shortcode() {
  
	add_shortcode( 'jay-login-form', 'njengah_login_form_shortcode' );
  
}

//Step 2: Shortcode callback
function njengah_login_form_shortcode() {
	
	if (is_user_logged_in())
		
		return '<p>Welcome. You are logged in!</p>'; ?> 
		
		<div class ="njengah-login-tutorial" >  
		
			<?php  return wp_login_form( 
							array( 
								'echo' => false ,
								'label_username' => __( 'Your Username ' ),
								'label_password' => __( 'Your Password' ),
								'label_remember' => __( 'Remember Me' )
			              )
			); ?> 
		
		</div>
  <?php 
   }

// Step 3 : Init the shortcode function
add_action( 'init', 'njengah_add_login_shortcode' );

// $body   = array(
//     'data_send' => $data_send,
//     "email" => "ivanko.vano2010@gmail.com",
//     "password" => "sdfyxevfr",
//     "token" => "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjYyN2NmNjVmMDRiODg5MDAyOTBkMjZkYiJ9.bqu2Jt1dBC-bRBhhJWt6lTbT0huqweAm0AgNQZ6cMdQ"
// );
// $result = wp_remote_post('https://dev-auth.id.dubaifuture.ae/api/v1/auth/login', array(
//     'method'      => 'POST',
//     'redirection' => 1,
//     'httpversion' => '1.0',
//     'blocking'    => true,
//     'headers'     => array(),
//     'body'        => $body,
//     'cookies'     => array(),
// ));
// if (is_wp_error($result)) {
//     // вернуть ошибку
// }

// $body     = $result['body'];
// $body_array     = json_decode($body);
// $success = $body_array->success;
// if (!$success) {
//     // вернуть ошибку
// }
// $data = $body_array->data;
// print_r($body_array);