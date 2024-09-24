<footer class="footer">
    <div class="container container--footer">
        <nav class="nav nav--footer">
            <ul class="footer-menu">
                <li class="footer-menu__item footer-menu__item--copyright">
                    &copy; $CurrentDatetime.Format("y") $SiteConfig.Title
                </li>
                <% loop $MenuSet('FooterMenu').MenuItems %>
                    <li class="footer-menu__item">
                        <a href="$Link" class="footer-menu__link">$MenuTitle</a>
                    </li>
                <% end_loop %>
            </ul>
        </nav>
        <a href="$baseURL" class="footer__logo">
            <img src="$resourceURL('themes/startup/images/logo--silverstripe-cms.svg')" alt="{$SiteConfig.Title}">
        </a>
    </div>
</footer>
