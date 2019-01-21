<div <% if $Name %>id="$Name"<% end_if %> class="field<% if extraClass %> $extraClass<% end_if %>">
	$Field
	<% if $Message %><small class="error $MessageType">$Message</small><% end_if %>
	<% if $Description %><span class="description">$Description</span><% end_if %>
</div>