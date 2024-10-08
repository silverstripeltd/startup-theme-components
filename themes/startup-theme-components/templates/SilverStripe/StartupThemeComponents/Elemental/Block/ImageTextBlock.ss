<div class="container">
    <div class="{$BlockNameClass} {$BlockVariantClass}">
        <div class="{$BlockNameClass}__right-column">
            <% if $ShowTitle && $IsFirstBlock %>
                <h1 class="block__h1">$Title</h1>
            <% else_if $ShowTitle %>
                <h2 class="block__h2">$Title</h2>
            <% end_if %>

            <% if $Content %>
                <div class="{$BlockNameClass}__content typography">
                    $Content
                </div>
            <% end_if %>

            <% if $CTAButtonLink %>
                <a href="$CTAButtonLink.URL"
                   class="button {$BlockNameClass}__button <% if $CTAButtonLink.OpenInNew %> button--external<% end_if %>"
                   <% if $CTAButtonLink.OpenInNew %>target="_blank" rel="noopener noreferrer"<% end_if %>
                >
                    $CTAButtonLink.Title
                </a>
            <% end_if %>
        </div>
        <div class="{$BlockNameClass}__left-column">
            <picture>
                <source media="(min-width: 992px)" srcset="$ImageTextBlockImage.ScaleWidth(885).URL"/>
                <source media="(min-width: 750px)" srcset="$ImageTextBlockImage.ScaleWidth(675).URL"/>
                <source media="(min-width: 450px)" srcset="$ImageTextBlockImage.ScaleWidth(1065).URL"/>
                <img src="$ImageTextBlockImage.ScaleWidth(615).URL" alt="$ImageTextBlockImage.Title" />
            </picture>
        </div>
    </div>
</div>
