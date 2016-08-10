<!-- Module jGalleryView2 -->
<script type="text/javascript">
{literal}
$(document).ready(function(){
	$('#gallery').galleryView({
		panel_width:          540 //Width of panel
		,panel_height:        205 //Height of panel
		,frame_width:         80 //Width of filmstrip frame
		,frame_height:        30 //Height of filmstrip frame
		,overlay_height:      50 //Height of panel overlay
		,overlay_font_size:   '1em' //Default font size of panel overlay
		,overlay_position:    'bottom' // Position of overlay within panel
		,filmstrip_position:  'bottom' //Position of filmstrip within gallery
		,transition_speed:    300 //Duration of transition animation
		,transition_interval: 6000 //Length of time between transitions (0 = no automatic transitions)
		,overlay_opacity:     0.4 //Opacity of panel overlay background
		,overlay_color:       '#000000' //Color of panel overlay background
		,background_color:    'transparent' //Color of frame background and filmstrip background
		,overlay_text_color:  'white' //Color of text in panel overlay
		,caption_text_color:  'gray' //Color of text in filmstrip captions
		,border:              '1px solid black' //Size, style and color of gallery border
		,nav_theme:           'dark' //Color of navigation buttons and frame pointer
		,easing:              'easeInOutQuad' // Controls animation of filmstrip and pointer
		,show_captions:       false //Determines whether or not frame captions are displayed
		,fade_panels:         true //Determines whether panels fade during transitions or switch instantly.
		,pause_on_hover:      false //If true, animations will pause when the mouse hovers over the panel (requires 500ms hover to pause)
	});
});
{/literal}
</script>

<div id="galleryviewHolder" align="center" style="padding:0 0 10px 0;overflow: hidden; clear:both">
	<div id="wrap">
		<ul id="gallery">
			{foreach from=$xml->item item=my_item name=loop}
			<li>
				{if $my_item->img}<img src="{$module_dir}{$my_item->img}" alt="{$smarty.foreach.loop.index}" />{/if}
				<div class="panel-overlay">
					{if $my_item->$title}
					<h3>
						{if $my_item->url}<a href="{$my_item->url}">{/if}
						{$my_item->$title}
						{if $my_item->url}</a>{/if}
					</h3>
					{/if}
					{if $my_item->$text}<p>{$my_item->$text}</p>{/if}
				</div>
			</li>
			{/foreach}
		</ul>
	</div>
</div>
<!-- /Module jGalleryView2 -->
