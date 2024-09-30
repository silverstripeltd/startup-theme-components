<header class="header">
    <div class="container container--header">
        <div class="header-main">
            <a href="$baseURL" class="logo">
                <img src="$resourceURL('themes/startup/images/logo--white.svg')" alt="{$SiteConfig.Title}">
            </a>

            <%-- Desktop menu --%>
            <nav class="nav nav--desktop">
                <ul class="menu">
                    <% loop $MenuSet('MainMenu').MenuItems %>
                        <li class="menu__item<% if $Children %> menu__item--has-submenu<% end_if %>">
                            <a href="$Link" class="menu__link" <% if $IsNewWindow %>target="_blank" rel="noopener noreferrer"<% end_if %>>$MenuTitle</a>
                            <% if $Children %>
                                <ul class="submenu">
                                    <% loop $Children %>
                                        <li class="submenu__item">
                                            <a href="$Link" class="submenu__link">$MenuTitle</a>
                                        </li>
                                    <% end_loop %>
                                </ul>
                            <% end_if %>
                        </li>
                    <% end_loop %>
                </ul>
            </nav>
        </div>
        <%-- SiteConfig Header Button Link  --%>
        <% with $SiteConfig  %>
            <% if $HeaderButton %>
                <div class="header-button">
                    <button class="button">$HeaderButton</button>
                </div>
            <% end_if %>
        <% end_with %>
        <%-- Mobile menu controls --%>
        <button class="hamburger" type="button" aria-label="Toggle menu" data-toggle-mobile-menu>
            <span class="hamburger__lines"></span>
        </button>

        <%-- Mobile menu background - can be clicked to close menu --%>
        <div class="modal__background" data-toggle-mobile-menu></div>

        <%-- Mobile menu --%>
        <nav class="nav nav--mobile">
            <a href="$BaseHref" class="logo logo--mobile">
                <img class="logo__image" src="$resourceURL('themes/startup/images/logo--black.svg')" alt="{$SiteConfig.Title}">
            </a>
            <ul class="mobile-menu" data-accordion>
                <% loop $MenuSet('MainMenu').MenuItems %>
                    <li class="mobile-menu__item<% if $Children %> mobile-menu__item--has-submenu<% end_if %>" <% if $Children %>data-accordion-item<% end_if %>>
                        <a href="$Link" class="mobile-menu__link" <% if $IsNewWindow %>target="_blank" rel="noopener noreferrer"<% end_if %>
                        >$MenuTitle</a>
                        <% if $Children %>
                            <button class="submenu-chevron" type="button" aria-label="Open $MenuTitle submenu" aria-expanded="false" data-accordion-link>
                                <svg width="11" height="8" viewBox="0 0 11 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0 1.88973L1.29663 0.5L5.50183 5.08612L9.70337 0.5L11 1.88973L5.50183 7.86607L0 1.88973Z" fill="#2D282870"/>
                                </svg>
                            </button>
                            <div class="mobile-submenu-container">
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
            <%-- SiteConfig Mobile nav Header Button Link  --%>
            <% with $SiteConfig  %>
                <% if $HeaderButton %>
                    <div class="mobile-menu__button">
                        <button type="button" class="button">$HeaderButton</button>
                    </div>
                <% end_if %>
            <% end_with %>
        </nav>
    </div>
</header>
