<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Android :: Doolally</title>
	<?php echo $mobileStyle; ?>
    <?php echo $androidStyle; ?>
</head>
<body class="androidHome">
<!-- Status bar overlay for full screen mode (PhoneGap) -->
<div class="statusbar-overlay"></div>
<!-- Panels overlay-->
<div class="panel-overlay"></div>
<!-- Left panel with reveal effect-->
<div class="panel panel-left panel-reveal">
    <div class="content-block">
        <p>Left panel content goes here</p>
    </div>
</div>
<!-- Views -->
<div class="views tabs toolbar-through">
    <!-- Your main view, should have "view-main" class -->
    <div id="tab1" class="view view-commingUp tab">
        <!-- Top Navbar-->
        <div class="navbar">
            <div class="navbar-inner">
                <div class="left">
                    <a href="#" class="link icon-only open-panel"><i class="icon icon-bars"></i></a>
                </div>
                <div class="center sliding">Awesome App</div>
            </div>
        </div>

        <!-- Pages container, because we use fixed-through navbar and toolbar, it has additional appropriate classes-->
        <div class="pages navbar-fixed">
            <!-- Page, "data-page" contains page name -->
            <div data-page="comming-up" class="page">
                <!-- Scrollable page content -->
                <div class="page-content">
                    <p>Page content goes here</p>
                    <!-- Link to another page -->
                    <a href="<?php echo base_url();?>mobile/about" onclick="myApp.showIndicator()">About app</a>
                </div>
            </div>
        </div>
    </div>
    <div id="tab2" class="view view-main tab active">
        <!-- Top Navbar-->
        <div class="navbar">
            <div class="navbar-inner">
                <div class="left">
                    <a href="#" class="link icon-only open-panel"><i class="icon icon-bars"></i></a>
                </div>
                <div class="center sliding">Awesome App</div>
            </div>
        </div>
        <!-- Pages container, because we use fixed-through navbar and toolbar, it has additional appropriate classes-->
        <div class="pages navbar-fixed">
            <!-- Page, "data-page" contains page name -->
            <div data-page="main-feeds" class="page">
                <!-- Scrollable page content -->
                <div class="page-content">
                    <p>Tab 2</p>
                </div>
            </div>
        </div>
    </div>
    <div id="tab3" class="view view-menus tab">
        <!-- Top Navbar-->
        <div class="navbar">
            <div class="navbar-inner">
                <div class="left">
                    <a href="#" class="link icon-only open-panel"><i class="icon icon-bars"></i></a>
                </div>
                <div class="center sliding">Awesome App</div>
            </div>
        </div>
        <!-- Pages container, because we use fixed-through navbar and toolbar, it has additional appropriate classes-->
        <div class="pages navbar-fixed">
            <!-- Page, "data-page" contains page name -->
            <div data-page="menusPage" class="page">
                <!-- Scrollable page content -->
                <div class="page-content">
                    <p>Tab 3</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Bottom Toolbar-->
    <div class="toolbar tabbar tabbar-labels">
        <div class="toolbar-inner">
            <a href="#tab1" class="tab-link">
                <i class="fa fa-calendar fa-2x"></i>
                <span class="tabbar-label">Comming Up</span>
            </a>
            <a href="#tab2" class="tab-link active">
                <i class="icon fa fa-beer fa-2x">
                    <span class="badge bg-red">5</span>
                </i>
                <span class="tabbar-label">#Doolally</span>
            </a>
            <a href="#tab3" class="tab-link">
                <i class="fa fa-spoon fa-2x"></i>
                <span class="tabbar-label">Menus</span>
            </a>
        </div>
    </div>
</div>
</body>
<?php echo $mobileJs; ?>
<?php echo $androidJs; ?>
</html>