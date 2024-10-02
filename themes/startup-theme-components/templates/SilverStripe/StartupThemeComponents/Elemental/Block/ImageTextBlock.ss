<div class="image-text-block">
    <div class="container image-text-block__container $VariantClass">
        <div class="image-text-block__left-column">
            <div class="image-text-block__image-wrap">
                <img src="$ImageTextBlockImage.URL" class="image-text-block__image" alt="$ImageTextBlockImage.AltText"
                     loading="lazy"/>
            </div>
        </div>
        <div class="image-text-block__right-column">
            <% if $Title && $ShowTitle %>
                <% if $Content %>
                    <h2>$Title</h2>
                <% else %>
                    <h1>$Title</h1>
                <% end_if %>
            <% end_if %>
            <% if $Content %>
                <div class="image-text-block__content">
                    $Content
                </div>
            <% end_if %>
            <% if $CTAButtonLink %>
                <button class="button image-text-block__button">$CTAButtonLink</button>
            <% end_if %>
        </div>
    </div>
</div>
