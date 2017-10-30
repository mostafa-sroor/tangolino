<html lang="en"><head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Collage</title>
    <meta name="generator" content="Bootply">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Bootstrap Template with 3-column layout using fixed navbar, left sidebar nav and 2 scrolling content columns. example.">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0-rc1/css/bootstrap.min.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="apple-touch-icon" href="/bootstrap/img/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/bootstrap/img/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/bootstrap/img/apple-touch-icon-114x114.png">

    <!-- CSS code from Bootply.com editor -->

    <style type="text/css">
        body {
            padding-top:65px;
        }

        @media (min-width: 979px) {

            #midCol.affix-top {
                position:fixed;
                width:265px;
                margin-right:10px;

            }

            #midCol.affix {
                position:static;
                width:100%;
            }

        }

        @media (min-width: 767px) {
            .affix,.affix-top {
                position:fixed;
            }
        }

    </style>
</head>

<!-- HTML code from Bootply.com editor -->

<body>

<!-- Fixed navbar -->
<div class="navbar navbar-fixed-top">
    <div class="container">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">World</a>
        <div class="nav-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li class="dropdown-header">Header</li>
                        <li><a href="#">Separated link</a></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav pull-right">
                <li><a href="#">Link</a></li>
                <li><a href="#">Login</a></li>
                <li><a href="#"><i class="glyphicon glyphicon-user"></i></a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>


<!-- Content -->
<div class="container">
    <div class="row">
        <div class="col-sm-2">
            <ul id="sidebar" class="nav nav-stacked affix">
                <li><a href="#">The Next Web</a></li>
                <li><a href="#">Mashable</a></li>
                <li><a href="#">TechCrunch</a></li>
                <li><a href="#">GitHub</a></li>
                <li><a href="#">In1</a></li>
                <li><a href="#">TechMeMe</a></li>
            </ul>




        </div>
        <div class="col-sm-3">

           <a href="https://goo.gl/ZGLYnF" target="_blank">https://goo.gl/ZGLYnF</a>


            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js">
            </script>
            <script>
                var jqxz19872 = $.noConflict();
                jqxz19872(document).ready(function(){
                    var token  = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiIxMjkiLCJhdWQiOiJhZG1pblwvbG9va1wvYVwvc2hvcHBhYmxlY29udGVudCIsImlhdCI6MCwibmJmIjoxNTA2NTE5NjU2fQ.8jmEJ3MrImyz9JQa-lTKLFCN9Pmp3a03VY0i1TsOxdk";
                    var url    = "https://tview.tangolino.com/admin/look/a/tagged-photo";

                    var host   = window.location.host;
                    jqxz19872.ajax({
                        url  : url,
                        type : "get",
                        data : {token:token,
                            host:host,
                        },
                        success : function(result) {
                            jqxz19872("#block_content_ads").html(result);
                        },
                    });

                });
            </script>
            <div id="block_content_ads"
                 style=" position: absolute ;  z-index:190000000">
            </div>

        </div>
        <div class="col-sm-7">
            <div class="row">
                <div class="col-sm-8"><h2>Lighweight</h2><p>The new Bootstrap 3 is a smaller build. The separate Bootstrap
                        base and responsive.css files have now been merged into one. There is no
                        more fixed grid, only fluid.</p></div>
                <div class="col-sm-4"><img class="img-responsive" src="//placehold.it/220x180/666666/FFF"></div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-8"><h2>Lighweight</h2><p>The new Bootstrap 3 is a smaller build. The separate Bootstrap
                        base and responsive.css files have now been merged into one. There is no
                        more fixed grid, only fluid.</p></div>
                <div class="col-sm-4"><img class="img-responsive" src="//placehold.it/220x180/666666/FFF"></div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-4"><img class="img-responsive" src="//placehold.it/220x180/777777/FFF"></div>
                <div class="col-sm-8"><h2>Large, Small or Tiny</h2><p>
                        The new fluid grid comes in 3 flavors, or actually sizes. The large grid <code>col-lg-*</code> works exactly like the Bootstrap 2.x <code>span*</code> did.
                        There is also a small grid that is realized using the <code>col-sm-*</code> classes. This smaller grid is ideal for smartphones and tablets.
                        Finally, there is the non-stacking tiny grid <code>col-*</code> that is intended for very small screens less that 480px.
                    </p></div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-8"><h2>A Playground</h2><p>
                        Bootply is a playground for Bootstrap. Designers and developers use Bootply to edit, design, prototype, test and find examples that use Bootstrap 3.
                        Use Bootply to hand-code HTML, Javascript, CSS and drop in the Bootstrap classes. There is a also a visual drag-and-drop builder that is perfect for wire-framing and mockups.
                    </p></div>
                <div class="col-sm-4"><img class="img-responsive" src="//placehold.it/220x180/777777/FFF"></div>
            </div>
            <hr>
        </div>
    </div>
</div>


<script async="" src="//www.google-analytics.com/analytics.js"></script><script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


<script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.0.0-rc1/js/bootstrap.min.js"></script>







<!-- JavaScript jQuery code from Bootply.com editor  -->

<script type="text/javascript">

    $(document).ready(function() {



    });

</script>

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-40413119-1', 'bootply.com');
    ga('send', 'pageview');
</script>



</body></html>