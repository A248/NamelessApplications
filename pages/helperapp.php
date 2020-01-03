<?php 
/* 
 *	Made by Partydragen
 *  http://partydragen.com/
 *
 *  License: MIT
 *
 *  Refactored by A248
 */

// HTMLPurifier
require('core/includes/htmlpurifier/HTMLPurifier.standalone.php');

/*
 *  User must be logged in
 */
if(!$user->isLoggedIn()){
	Redirect::to('/signin');
	die();
}


/*
 *  Check if page is enabled
 */
$helperapp = $queries->getWhere('addons', array('name', '=', 'HelperApp'));
if($helperapp[0]->enabled == 0){
	Redirect::to('/');
	die();
}

/* 
 *  Handle input
 */
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		// Get all answers into one string
		unset($_POST['token']);
		
		$content = array();
		foreach($_POST as $key => $item){
			$content[] = array($key, htmlspecialchars($item));
		}
		
		$content = json_encode($content);
		
		$queries->create('helperapp_replies', array(
			'uid' => $user->data()->id,
			'time' => date('U'),
			'content' => $content
		));
		
		$app_id = $queries->getLastId();
		
		// Moderator alerts
		$mod_groups = $queries->getWhere('groups', array('helperapp', '=', 1));
		foreach($mod_groups as $mod_group){
			$mod_users = $queries->getWhere('users', array('group_id', '=', $mod_group->id));
			foreach($mod_users as $individual){
				$queries->create('alerts', array(
					'user_id' => $individual->id,
					'type' => $helperapp_language['helper_app'],
					'url' => '/mod/helperapp/?app=' . $app_id,
					'content' => str_replace('{x}', htmlspecialchars($user->data()->username), $helperapp_language['new_helper_app_submitted_alert']),
					'created' => date('U')
				));
			}
		}
		
		Session::flash('app_success', '<div class="alert alert-success">' . $user_language['application_submitted'] . '</div>');
		$completed = 1;
		
	} else {
		// Invalid token
		Session::flash('app_succes', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
	}
}

if(!isset($completed)){
	// Has the user already submitted a Helper Application?
	$already_submitted = $queries->getWhere('helperapp_replies', array('uid', '=', $user->data()->id));
	foreach($already_submitted as $item){
		if($item->status == 0){
			$completed = 2;
			break;
		}
	}
	$already_submitted = null;
}

$page = $helperapp_language['helper_app'];

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Helper Application page for the <?php echo $sitename; ?> community">
    <meta name="author" content="<?php echo $sitename; ?>">
	<meta name="robots" content="noindex">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $helperapp_language['helper_app'];
	
	require('core/includes/template/generate.php');
	?>
	
	<!-- Custom style -->
	<style>
	html {
		overflow-y: scroll;
	}
	</style>
	
  </head>

  <body>
	<?php
	// Load navbar
	$smarty->display('styles/templates/' . $template . '/navbar.tpl');
	?>
	<br />
	<div class="container">
	<?php 
	    if(Session::exists('staff_app')){
		  echo Session::flash('staff_app');
	    }
	?>
	  <div class="well">
		<h2><?php echo $helperapp_language['helper_app']; ?></h2>
		<?php
		if(!isset($completed)){
		?>
		<div class="row">
		  <div class="col-md-5">
			<form action="" method="post">
			<?php 
			// Get all questions
			$questions = $queries->getWhere('helperapp_questions', array('id', '<>', 0)); 
			
			foreach($questions as $question){
				if($question->type == 3){
					// text area
			?>
			  <label for="<?php echo htmlspecialchars($question->name); ?>"><?php echo htmlspecialchars($question->question); ?></label>
			  <textarea class="form-control" id="<?php echo htmlspecialchars($question->name); ?>" name="<?php echo $question->id; ?>"></textarea><br />
			<?php
				} else if($question->type == 1){
					// dropdown
			?>
			  <label for="<?php echo htmlspecialchars($question->name); ?>"><?php echo htmlspecialchars($question->question); ?></label>
			  <select name="<?php echo $question->id; ?>" id="<?php echo htmlspecialchars($question->name); ?>" class="form-control">
			    <?php
				$options = explode(',', $question->options);
				foreach($options as $option){
				?>
				  <option value="<?php echo htmlspecialchars($option); ?>"><?php echo htmlspecialchars($option); ?></option>
				<?php
				}
				?>
			  </select><br />
			<?php
				} else {
					// normal input tag
			?>
			  <label for="<?php echo htmlspecialchars($question->name); ?>"><?php echo htmlspecialchars($question->question); ?></label>
			  <input type="text" class="form-control" id="<?php echo htmlspecialchars($question->name); ?>" name="<?php echo $question->id; ?>"><br />
			<?php
				}
			}
			
			?>
			  <br />
			  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			  <input type="submit" class="btn btn-primary" value="<?php echo $helperapp_language['submit']; ?>">
			</form>
		  </div>
		</div>
		<?php
		} else {
			if(Session::exists('app_success')){
				echo Session::flash('app_success');
			}
			if($completed === 2){
		?>
		<div class="alert alert-info"><?php echo $helperapp_language['helper_app_already_submitted']; ?></div>
		<?php
			}
		}
		?>
	  </div>
    </div>
    <?php
	// Footer
	require('core/includes/template/footer.php');
	$smarty->display('styles/templates/' . $template . '/footer.tpl');
	
	// Scripts 
	require('core/includes/template/scripts.php');
	?>
  </body>
</html>
