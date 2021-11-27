<ul class="ca-menu">
                    <li <?php echo $active == "howtouse"?'class="active"':"";  ?>>
                        <a href="<?php echo $this->config->item('base_url'); ?>howtouse">
                            <span class="ca-icon" id="home">R</span>
                            <div class="ca-content">
                                <h2 class="ca-main">How to use</h2>
                                <h3 class="ca-sub">Learn more about MultiScraper</h3>
                            </div>
                        </a>
                    </li>
                    <li <?php echo $active == "settings"?'class="active"':"";  ?>>
                        <a href="<?php echo $this->config->item('base_url'); ?>">
                            <span class="ca-icon" id="download">S</span>
                            <div class="ca-content">
                                <h2 class="ca-main">Settings</h2>
                                <h3 class="ca-sub">Change common settings</h3>
                            </div>
                        </a>
                    </li>
                    <li <?php echo $active == "tasks"?'class="active"':"";  ?>>
                        <a href="<?php echo $this->config->item('base_url'); ?>tasks">
                            <span class="ca-icon" id="buy">p</span>
                            <div class="ca-content">
                                <h2 class="ca-main">Tasks</h2>
                                <h3 class="ca-sub">Manage tasks for MultiScraper</h3>
                            </div>
                        </a>
                    </li>
                    <li <?php echo $active == "log"?'class="active"':"";  ?>>
                        <a href="<?php echo $this->config->item('base_url'); ?>log">
                            <span class="ca-icon" id="contact">c</span>
                            <div class="ca-content">
                                <h2 class="ca-main">Log</h2>
                                <h3 class="ca-sub">Monitor its behavior</h3>
                            </div>
                        </a>
                    </li>
                    <li <?php echo $active == "contact"?'class="active"':"";  ?>>
                        <a href="<?php echo $this->config->item('base_url'); ?>manual">
                            <span class="ca-icon" id="guest">`</span>
                            <div class="ca-content">
                                <h2 class="ca-main">Manual Launch</h2>
                                <h3 class="ca-sub">Test your MultiScraper</h3>
                            </div>
                        </a>
                    </li>
</ul>