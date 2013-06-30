{if !isset($page)}
<div class="ajaxHeader">
	<strong>Page</strong>
	Add a new page to the CMS.
</div>
{/if}

<form method="post" action="{$action}">
	<div>Page alias</div>
	<input type="text" name="alias_page" value="{if isset($page)}{Security::out($page->get('alias_page'))}{/if}" />

	<div>Page title</div>
	<input type="text" name="title_page" value="{if isset($page)}{Security::out($page->get('title_page'))}{/if}" />

	<div>Page description</div>
	<input type="text" name="description_page" value="{if isset($page)}{Security::out($page->get('description_page'))}{/if}" />

	<div>Keywords</div>
	<input type="text" name="keywords_page" value="{if isset($page)}{Security::out($page->get('keywords_page'))}{/if}" />

	<div>Robots</div>
	<input type="text" name="robots_page" value="{if isset($page)}{Security::out($page->get('robots_page'))}{/if}" />

	<div>Author</div>
	<input type="text" name="author_page" value="{if isset($page)}{Security::out($page->get('author_page'))}{/if}" /><br />

	<button type="submit" class="btn btn-success">Save</button>
</form>