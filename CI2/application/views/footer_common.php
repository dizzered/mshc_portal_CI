
	</div>
</div> <!-- .container -->

<div class="footer">
	<div class="footer_ins">
		<?php
		if ($is_mobile 
			&& $this->session->userdata('site_state')
			&& $this->session->userdata('site_state') == 'view_full_site')
		{
			?>
			<p class="footer-view" ><a href="<?php echo base_url(); ?>home/view_mobile_site">View Mobile Version</a></p>
			<?php
		}
		?>
    	<p>© 2013 Advanced Medical Management, Inc. All rights reserved.</p>
    </div>
</div>

</body>
</html>

<script>
$(function() {
	if (loadTable) $('#' + tableName + '-table-container').jtable('load');
	
	if ($('#' + tableName + '-table-container').length > 0) window['append'+tableID+'SearchBar']();
});
</script>