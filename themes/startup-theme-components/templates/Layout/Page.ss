<main id="main" class="container container--page" tabindex="-1">
    <% if not $isHomePage %>
        $Breadcrumbs
    <% end_if %>
    <div class="page">
        <div class="page__content">
            <h1 class="page__title">$Title</h1>
            <% if $Intro %>
                <p class="intro page__intro">$Intro</p>
            <% end_if %>
            $Content
        </div>
        <% if $ShowSiblingMenu && $Menu($PageLevel).count > 1 && $PageLevel > 1 %>
            <aside class="page-menu">
                <nav class="page-menu__nav" aria-labelledby="page-menu-heading">
                    <h2 id="page-menu-heading" class="h5 page-menu__heading">
                        <a href="$Parent.Link" class="page-menu__heading-link">$Parent.Title</a>
                    </h2>
                    <ul class="page-menu__list">
                    <% loop $Menu($PageLevel) %>
                        <% if $isCurrent %>
                            <li class="page-menu__list-item page-menu__list-item--current">$Title</li>
                        <% else %>
                            <li class="page-menu__list-item"><a href="$Link">$Title</a></li>
                        <% end_if %>
                    <% end_loop %>
                    </ul>
                </nav>
            </aside>
        <% end_if %>
    </div>
</main>
