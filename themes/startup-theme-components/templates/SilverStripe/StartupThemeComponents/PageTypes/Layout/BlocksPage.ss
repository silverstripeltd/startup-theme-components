<main>
    <% if $ShowHero %>
        <div class="hero">
            <div class="container">
                <div class="hero__inner">
                    $Breadcrumbs
                    <h1 class="hero__title">$Title</h1>
                    <% if $Intro %>
                        <p class="intro hero__intro">$Intro</p>
                    <% end_if %>
                </div>
            </div>
        </div>
    <% end_if %>
    $ElementalArea
</main>
