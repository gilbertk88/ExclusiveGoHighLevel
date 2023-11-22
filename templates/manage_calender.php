<?php

$hl_calender_iframe_url = get_option('hl_calender_iframe_url');

if( !$hl_calender_iframe_url ){
	$hl_calender_iframe_url = '';
}

// var_dump( $hl_calender_iframe_url );

?>
<div class="wrap gohighlevel_wrap">

<h1> Go High Level Calendar </h1>

<form method="post" action="options.php" novalidate="novalidate">

	<table class="form-table" role="presentation">

		<tbody>
			
		<tr>
			<th scope="row"><label for="home">Shortcode</label></th>
			<td>
				<p class="description-shortcode_details" id="home-description">[high_level_calendar]</p>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="blogname">Iframe URL</label></th>
			<td>
				<input name="hl_iframe_url" type="text" id="hl_iframe_url" value="<?php echo $hl_calender_iframe_url ; ?>" class="regular-text">
				<br><span id="hl_required_field"></span>

				<div style="color: #333 ;">
					<p ">
						<br><br><b>Steps to add a parameter to the Go High Level calendar;</b><br><br>

						There are two types of parameters that can work with the Go High Level calendar. And they are as follows:<br>

						<ul>
							<li>* The "source" parameter: This parameter is auto generated and passed to the go high level calendar.</li>
							<li>* The dynamically added parameters: The calender allows you to pass parameters on the fly. To do so follow the following guidelines.</li>
						</ul><br><br>

						To add the parameters follow the following steps:<br>

						<ul>
							<li>* Add the parameters you wish to have on the on go high level side.</li>
							<li>* The 'source' parameter will be automatically be generate by the plugin.</li>
							<li>* The dynamic parameters are added as follows, add the some $_GET parameters with the names of the parameters being identical with the ones on the on go high level;</li>
						</ul>

						For example assuming that the wordpress website page that is displaying the calender is at https://[MY_WORDPRESS_WEBSITE_PAGE]/page-name <br>
						Lets add three parameters; date, name, and age.<br>
						We'll add them as follows: https://[MY_WORDPRESS_WEBSITE_PAGE]/page-name?date='23-2-2023'&name='John'&age='35'<br/>

						The above will send the following values to the go high level calendar:<br>
						<ul>
							<li>* Parameter "date" that will have the value '23-2-2023'</li>
							<li>* Parameter "name" that will have the value 'John'</li>
							<li>* Parameter "age" that will have the value '35'</li>
						</ul>
						
						<br/><br/>

						Thank you.
						
					</p>
				</div>
			</td>
		</tr>

		</tbody>

	</table>

<p class="submit">
	<input type="submit" name="submit" id="iframe_submit" class="button button-primary" value="Save Changes">
	<span id="hl_update_progress" ></span>
</p>

</form>

</div>