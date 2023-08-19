    <head>
        <% base_tag %>
        <title><% if $MetaTitle %>$MetaTitle<% else %>$Title<% end_if %><% if not $IgnoreSiteName %> | $SiteConfig.Title<% end_if %></title>
        $MetaTags(false)
        <meta name="viewport" content="width=devfreice-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <meta name="format-detection" content="telephone=no">
        <meta name="skype_toolbar" content="skype_toolbar_parser_compatible">
        <link type="image/x-icon" rel="Shortcut Icon" href="{$ThemeDir}/img/favicon.ico">


        $SiteConfig.HeaderGlobalAddonScript
        $HeaderAddonScript
    </head>
