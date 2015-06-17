<form id="searchwidgetcont"  class="form-search center" role="search" method="get" action="<?php echo esc_url( $searchPageLink ); ?>">
	<div id="searchwidgetdiv" class="input-prepend">
		<input id="searchwidgetinput" type="text" class="span2 search-query" name="search-product" placeholder="<?php esc_attr_e( 'Search', 'rt_gantry_wp_lang' ); ?>" value="<?php echo wp_kses( get_query_var('search-product'), null ); ?>">
		<button id="searchwidgetbutton" type="submit" class="btn btn-primary" value="<?php esc_attr_e( 'Search', 'rt_gantry_wp_lang' ); ?>">Search</button>
	</div>
</form>