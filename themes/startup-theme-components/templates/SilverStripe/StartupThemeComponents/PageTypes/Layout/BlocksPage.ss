<main>
    <% if $ShowHero %>
        <div class="hero">
            <div class="container">
                $Breadcrumbs
                <h1 class="page__title">$Title</h1>
                <% if preg_replace('/\s+/', '', $Intro) %>
                    <p class="page__intro">$Intro</p>
                <% end_if %>
            </div>
        </div>
    <% end_if %>
    $ElementalArea
</main>
