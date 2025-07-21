<main id="main" class="container container--page" tabindex="-1">
    <% if not $isHomePage %>
        $Breadcrumbs
    <% end_if %>
    <div class="page">
        <div class="page__content <% if $Menu($PageLevel).count > 1 %>page__content--with-sidebar<% end_if %>">
            <h1 class="page__title">$Title</h1>
            <% if $Intro %>
                <p class="intro page__intro">$Intro</p>
            <% end_if %>
            $Content
        </div>
        <% if $ShowSiblingMenu && $Menu($PageLevel).count > 1 %>
            <% include Sidebar %>
        <% end_if %>
    </div>
</main>
