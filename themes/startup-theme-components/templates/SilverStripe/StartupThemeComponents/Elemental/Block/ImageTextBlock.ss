<div class="image-text-block">
    <div class="container image-text-block__container $VariantClass">
        <div class="image-text-block__left-column">
            <picture>
                <source media="(min-width: 1600px)" srcset="$ImageTextBlockImage.FocusFill(882, 710).URL"/>
                <source media="(min-width: 1200px)" srcset="$ImageTextBlockImage.FocusFill(780, 890).URL"/>
                <source media="(min-width: 576px)" srcset="$ImageTextBlockImage.FocusFill(530, 500).URL"/>
                $ImageTextBlockImage.LazyLoad(false)
            </picture>
        </div>
        <div class="image-text-block__right-column">
            <% if $ShowH1Title %>
                <h1 class="image-text-block__heading">$Title</h1>
            <% else %>
                <h2 class="image-text-block__heading">$Title</h2>
            <% end_if %>

            <% if $Content %>
                <div class="image-text-block__content typography">
                    $Content
                </div>
            <% end_if %>

            <% if $CTAButtonLink %>
                <a href="$CTAButtonLink.URL"
                   class="button image-text-block__button <% if $CTAButtonLink.OpenInNew %> button--external<% end_if %>"
                    <% if $CTAButtonLink.OpenInNew %>target="_blank" rel="noopener noreferrer"<% end_if %>>
                    $CTAButtonLink.Title
                </a>
            <% end_if %>
        </div>
    </div>
</div>
