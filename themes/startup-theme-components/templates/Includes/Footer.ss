<footer class="footer">
    <div class="container container--footer">
        <nav aria-label="Footer">
            <ul class="footer-menu">
                <li class="footer-menu__item footer-menu__item--copyright">
                    &copy; $now.Format("y")
                    <% if $SiteConfig.FooterCopyright %> 
                        $SiteConfig.FooterCopyright
                    <% else %>
                        $SiteConfig.Title
                    <% end_if %>
                </li>
                <% loop $MenuSet('FooterMenu').MenuItems %>
                    <li class="footer-menu__item">
                        <a href="$Link" class="footer-menu__link" <% if $IsNewWindow %>target="_blank" rel="noopener noreferrer"<% end_if %>>$MenuTitle</a>
                    </li>
                <% end_loop %>
            </ul>
        </nav>
        <a href="$baseURL" class="footer__logo">
            <img src="$resourceURL('themes/startup/images/logo--silverstripe-cms.svg')" width="176" height="21" alt="{$SiteConfig.Title}">
        </a>
    </div>
</footer>
