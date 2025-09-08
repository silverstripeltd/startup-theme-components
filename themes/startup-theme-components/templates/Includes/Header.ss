<% require themedCSS('startup-components') %>
<header class="header">
    <a href="#main" class="button button--on-dark button--skip">Skip to main content</a>

    <div class="container container--header">
        <a href="$baseURL" class="logo" aria-label="Home">
            <img src="$themedResourceURL('images/logo--white.svg')" width="117" height="22" alt="">
        </a>

        <%-- Desktop menu --%>
        <nav class="nav nav--desktop" aria-label="Main">
        <ul class="menu accordion">
            <% loop $Menu(1) %>
            <li class="menu__item<% if $Children %> menu__item--has-submenu accordion__item<% end_if %>" data-close-on-defocus>
            <div class="menu__item-container">
                <a id="{$URLSegment}-submenu-link" href="$Link" class="menu__link menu__link--{$LinkingMode}">$MenuTitle</a>
                <% if $Children %>
                    <button class="submenu-chevron accordion__toggle" type="button" aria-label="Open $MenuTitle submenu" aria-expanded="false" aria-controls="{$URLSegment}-submenu" data-accordion-flip>
                        <svg width="11" height="8" viewBox="0 0 11 8" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M0 1.88973L1.29663 0.5L5.50183 5.08612L9.70337 0.5L11 1.88973L5.50183 7.86607L0 1.88973Z" fill="currentcolor">
                        </svg>
                    </button>
                </div><%-- close .menu__item-container --%>
                    <div id="{$URLSegment}-submenu" class="submenu-container accordion__container" aria-labelledby="{$URLSegment}-submenu-link">
                        <ul class="submenu">
                            <% loop $Children %>
                                <li class="submenu__item">
                                    <a href="$Link" class="submenu__link submenu__link--{$LinkingMode}">$MenuTitle</a>
                                </li>
                            <% end_loop %>
                        </ul>
                    </div>
                <% else %>
                </div><%-- close .menu__item-container --%>
                <% end_if %>
                </li>
            <% end_loop %>
            </ul>
            </nav>

            <%-- SiteConfig header button link --%>
            <% with $SiteConfig  %>
                <% if $HeaderButton %>
                    <a class="button button--secondary-on-dark<% if $HeaderButton.OpenInNew %> button--external<% end_if %> header__button"
                       href="$HeaderButton.URL"
                        <% if $HeaderButton.OpenInNew %>target="_blank" rel="noopener noreferrer"<% end_if %>
                    >
                        $HeaderButton.Title
                    </a>
                <% end_if %>
            <% end_with %>

        <%-- Mobile menu controls --%>
        <button class="hamburger" type="button" aria-label="Toggle menu" data-toggle-mobile-menu>
            <span class="hamburger__lines"></span>
        </button>

        <%-- Mobile menu background - can be clicked to close menu --%>
        <div class="modal__background" data-toggle-mobile-menu></div>

        <%-- Mobile menu --%>
        <nav class="nav nav--mobile" aria-label="Main">
            <a href="$BaseHref" class="logo logo--mobile">
                <img class="logo__image" src="$themedResourceURL('images/logo--black.svg')" width="117" height="22" alt="{$SiteConfig.Title}">
            </a>
            <ul class="mobile-menu accordion">
                <% loop $Menu(1) %>
                    <li class="mobile-menu__item<% if $Children %> mobile-menu__item--has-submenu accordion__item<% end_if %>">
                        <a href="$Link" id="{$URLSegment}-mobile-submenu-link" class="mobile-menu__link"
                        >$MenuTitle</a>
                        <% if $Children %>
                            <button class="mobile-submenu-chevron accordion__toggle" type="button" aria-label="Open $MenuTitle submenu" aria-expanded="false" aria-controls="{$URLSegment}-mobile-submenu">
                                <svg width="11" height="8" viewBox="0 0 11 8" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" data-accordion-flip>
                                    <path d="M0 1.88973L1.29663 0.5L5.50183 5.08612L9.70337 0.5L11 1.88973L5.50183 7.86607L0 1.88973Z" fill="currentcolor"/>
                                </svg>
                            </button>
                            <div id="{$URLSegment}-mobile-submenu" class="mobile-submenu-container accordion__container" aria-labelledby="{$URLSegment}-mobile-submenu-link">
                                <ul class="mobile-submenu">
                                    <% loop $Children %>
                                        <li class="mobile-submenu__item">
                                            <a href="$Link" title="$Title" class="mobile-submenu__link">$MenuTitle</a>
                                        </li>
                                    <% end_loop %>
                                </ul>
                            </div>
                        <% end_if %>
                    </li>
                <% end_loop %>
            </ul>
            <%-- SiteConfig mobile nav header button link  --%>
            <% with $SiteConfig  %>
                <% if $HeaderButton %>
                    <a class="mobile-menu__button button<% if $HeaderButton.OpenInNew %> button--external<% end_if %>"
                       href="$HeaderButton.URL"
                        <% if $HeaderButton.OpenInNew %>target="_blank" rel="noopener noreferrer"<% end_if %>
                    >
                        $HeaderButton.Title
                    </a>
                <% end_if %>
            <% end_with %>
        </nav>
    </div>
</header>

