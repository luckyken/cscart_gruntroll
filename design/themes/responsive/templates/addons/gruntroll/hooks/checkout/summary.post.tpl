<script type="text/javascript">
	function showMilIdBox() {
		var milDiv = document.getElementById('milIdBox');
		var milLink = document.getElementById('milIdLink');
		milDiv.style.display = 'block';
		milLink.innerHTML='<a href="javascript:hideMilIdBox();">{__("mil_hide")}</a>'
	}
	
	function hideMilIdBox(){
		var milDiv = document.getElementById('milIdBox');
		var milLink = document.getElementById('milIdLink');
		milDiv.style.display = 'none';
		milLink.innerHTML='<a href="javascript:showMilIdBox();">{__("mil_discount")}</a>'
	}

</script>


			<tr>
                <td colspan="2" class="ty-checkout-summary__item">
                    <div id="milIdLink"><a href = "javascript:showMilIdBox()">{__("mil_discount")}</a></div>
					<div id="milIdBox" style="display:none;border:grey solid 1px;padding: 2px;">
    					<form class="cm-ajax cm-ajax-force cm-ajax-full-render" name="mil_id_form{$position}" action="{""|fn_url}" method="post">
        					<input type="hidden" name="result_ids" value="checkout*,cart_status*,cart_items,payment-methods" />
        					<input type="hidden" name="redirect_url" value="{$config.current_url}" />
            					<div class="ty-input-append">
                					<label for="mil_dob" class="hidden cm-required">{__("mil_dob")}</label>
                					<input type="text" class="ty-input-text cm-hint" id="mil_dob" name="mil_dob" size="40" value="{__("mil_dob_ex")}" />
                					<label for="mil_name" class="hidden cm-required">{__("mil_name")}</label>
                					<input type="text" class="ty-input-text cm-hint" id="mil_name" name="mil_name" size="40" value="{__("mil_name")}" />
                					<input type="text" class="ty-login__input cm-hint" id="mil_ssn" name="mil_ssn" size="40" value="{__("mil_ssn")}" />
									<div class="ty-checkout-buttons">
                                		{include file="buttons/button.tpl" but_meta="ty-btn__primary" but_name="dispatch[checkout.check_mil_id]" but_text=__("check_id")}
                            		</div>
            					</div>
    					</form>
					</div>
				</td>
            </tr>