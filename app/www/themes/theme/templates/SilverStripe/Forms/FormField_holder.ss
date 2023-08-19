<label for="$ID" class="field <% if $Message %>error<% end_if %>">
	<span class="field__text">$Title</span>
	$Field
	<% if $Description %><span class="description">$Description</span><% end_if %>
</label>