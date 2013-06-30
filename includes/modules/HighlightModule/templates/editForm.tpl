<div class="ajaxHeader">
	<strong>Highlight Module</strong>
	Edit the module settings
</div>

{if count($smarty.post) > 0}

	<div class="centerPaddingModal">
		<p> Updating module parameters <br /> successfully completed ! </p>
		<button class="btn btn-success nyroModalClose">Close modal</button>
	</div>


{else}

	<form method="post" action="{Server::getCurrentUrl()}" class="nyroModal">
		<table>
			<tr>
				<td style="width: 100px;"><label for="form_image">Image</label></td>
				<td><input type="text" name="image" id="form_image" value="{if isset($settings.image)}{$settings.image}{/if}" /></td>
			</tr>
			<tr>
				<td><label for="form_title">Title</label></td>
				<td><input type="text" name="title" id="form_title" value="{if isset($settings.title)}{$settings.title}{/if}" /></td>
			</tr>
			<tr>
				<td><label for="form_content">Content</label></td>
				<td><textarea cols="50" rows="5" name="content" id="form_content">{if isset($settings.content)}{$settings.content}{/if}</textarea></td>
			</tr>
			<tr>
				<td colspan="2""><button type="submit" class="btn btn-success">Save settings</button></td>
			</tr>
		</table>
	</form>

{/if}