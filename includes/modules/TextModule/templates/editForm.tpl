<div class="ajaxHeader">
	<strong>Simple Text Module</strong>
	Edit the module settings
</div>

<div class="centerPaddingModal">

{if count($smarty.post) > 0}

	<p> Updating module parameters <br /> successfully completed ! </p>
	<button class="btn btn-success nyroModalClose">Close modal</button>

{else}

	<form method="post" action="{Server::getCurrentUrl()}" class="nyroModal">
		<textarea cols="50" rows="5" name="content" id="form_content">{if isset($settings.content)}{$settings.content}{/if}</textarea> <br />
		<button type="submit" class="btn btn-success">Save settings</button>
	</form>

{/if}

</div>