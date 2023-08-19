<div class="elemental-preview">
    <a href="$CMSEditLink" class="elemental-edit">
        <div class="elemental-preview__icon">$Icon</div>

        <div class="elemental-preview__detail">
            <h4><% if $getPreviewTitle %>$getPreviewTitle <% end_if %><small>$Type</small></h4>

            <% if $Summary %>
                <p>$Summary.RAW</p>
            <% end_if %>
        </div>
    </a>
</div>
