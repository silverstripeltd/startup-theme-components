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
        <% if $PageLevel == 1 && $Children && $ShowSiblingMenu %>
            <% include LevelOneSidebar %>
        <% else_if $Menu($PageLevel).count > 1 && $ShowSiblingMenu %>
            <% include Sidebar %>
        <% end_if %>
    </div>
</main>
