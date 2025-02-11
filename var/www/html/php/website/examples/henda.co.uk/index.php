<?php


if(isset($_POST['input__email_address'])) {
    $subscribed = false;
    if(filter_var($_POST['input__email_address'], FILTER_VALIDATE_EMAIL)){

        $message  = 'request made from: ' . $_SERVER['REMOTE_ADDR'] . "\r\n";
        $message .= 'request made at: ' . date('Y-m-d H:i:s', time()) . "\r\n";
        $message .= 'captured email address: ' . strtolower($_POST['input__email_address']). "\r\n";

        $subscribed = @mail(
            'hello@aaronhenderson.co.uk',
            'website lead generation [ email subscription ]',
            $message
        );
    } else {
        header('Content-type: text/json', true, 500);
        echo json_encode(array(
            'error' => 'Invalid or malformed email address.'
        ));
        exit;
    }

    header('Content-type: text/json', true, 200);
    echo json_encode(array(
        'subscribed' => $subscribed
    ));
    exit;
}

if(function_exists('form_input') === false){
    function form_input($input, $default = ''){
        return isset($_POST[$input]) ? $_POST[$input] : $default;
    }

    if(isset($_POST['name'], $_POST['email'], $_POST['comments'])){

        if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){

            $message  = 'request made from: ' . $_SERVER['REMOTE_ADDR'] . "\r\n";
            $message .= 'request made at: ' . date('Y-m-d H:i:s', time()) . "\r\n";
            $message .= 'captured email address: ' . strtolower($_POST['email']). "\r\n";
            $message .= 'comment(s): ' . form_input('comments');

            @mail(
                'hello@aaronhenderson.co.uk',
                'website lead generation [ form submission ]',
                $message
            );

            $alerting['true'] = 'Contact form submission accepted.';
        }
    }
}

session_start();
?><!DOCTYPE html>
<html lang="en">
<head>
    <title>Creative content &amp; websites that make a difference - by Henda.co.uk</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="static/css/hootstrap.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
    <style>
        <?=str_replace(
                PHP_EOL,
                PHP_EOL . '        ',
                file_get_contents('static/css/henda.css')
           );
        ?>

    </style>
    <link rel="shortcut icon" href="/php/website/examples/henda.co.uk/favicon.ico">
</head>
<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">

<header id="header">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="/php/website/examples/henda.co.uk/" style="clear:both;">
                    Henda.co.uk
                </a>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav navbar-right text-uppercase">
                    <li><a href="#about">About</a></li>
                    <li><a href="#portfolio">Portfolio</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="jumbotron text-center">
        <div class="container-fluid">
            <h1 class="text-uppercase">Hello!</h1>
            <p>
                We specialize in <strong>creative content &amp; websites</strong> that
                <strong>make a difference</strong>
            </p>
            <form method="post" action="?newsletter_subscribe=true">
                <div class="input-group input-group-lg">
                    <input type="email"
                           class="form-control"
                           id="input__email_address"
                           name="input__email_address"
                           size="50"
                           placeholder="Email Address">
                    <div class="input-group-btn">
                        <button type="submit" id="newsletter__subscribe" class="btn btn-default">Tell me more</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</header>

<?php
if(isset($alerting['true'])):
?>
<div class="container-fluid">
    <div class="alert alert-success">
        <p><?=$alerting['true'];?></p>
    </div>
</div>
<?php
endif;
?>

<!-- Container (About Section) -->
<div id="about" class="container-fluid">
    <div class="row">
        <div class="col-sm-8">
            <h2>About our websites</h2><br>
            <h4>
                With over 15 years of experience delivering creative website solutions at a commercial &amp; enterprise
                level; we have proven results that measure in all aspects of industry.
            </h4>
            <br>
            <a href="#values" class="btn btn-primary btn-lg">Find out more</a>
            <a href="#contact" class="btn btn-secondary btn-lg">Get in Touch</a>
        </div>
        <div class="col-sm-4 text-right visible-sm visible-md visible-lg">
            <span class="glyphicon glyphicon-stats logo"
                  style="color: #F9AB00; -webkit-transform: scaleX(-1); transform: scaleX(-1);"></span>
        </div>
    </div>
</div>

<div id="values" class="container-fluid bg-grey">
    <div class="row">
        <div class="col-sm-4 visible-sm visible-md visible-lg ">
            <!--
            <img src="static/img/2019_DECROUBAIX_IMAGE_0242_BD.jpg"
                 alt="Co-location availabkle in france, germany, england and more">
            <span class="glyphicon glyphicon-globe logo" style="color: #aaa"></span>
            <img src="static/img/henda.jpg"  style="max-height: 150px;">
            <img src="static/img/henda.png"  style="max-height: 150px;">
            <img src="static/img/henda.jpg"  style="max-height: 350px;">
            -->
            <img src="static/img/2019_DEC_FR_ROUBAIX_IMAGE_0235_BD.jpg" alt="Persistent values" style="max-height: 260px">
        </div>
        <div class="col-sm-8">
            <h2>Our Values</h2>
            <h4>
                <strong>MISSION:</strong>
                Our mission is to provide solutions that work for you without unnecessary overheads; be that financial,
                cognitive or otherwise.
            </h4>
            <p>
                <strong>VISION:</strong> We are committed to maintaining a strong ethical
                foundation that champions the right values whilst upholding our service level agreements.
                We only take on projects that we can execute to a high standard of digital excellence and would love our
                next project to be yours.
            </p>
        </div>
    </div>
</div>

<!-- Container (Services Section) -->
<div id="arc" class="container-fluid text-center ">
    <h4>We offer efficiency in the following</h4>
    <br>
    <div class="row slideanim">
        <div class="col-xs-4">
            <span class="glyphicon glyphicon-pencil logo-small"></span>
            <h4>DESIGN</h4>
        </div>
        <div class="col-xs-4">
            <span class="glyphicon glyphicon-wrench logo-small"></span>
            <h4>DEVELOPMENT</h4>
        </div>
        <div class="col-xs-4">
            <span class="glyphicon glyphicon-time logo-small"></span>
            <h4>DELIVERY</h4>
        </div>
    </div>
</div>

<!-- Container (Portfolio Section) -->
<div id="portfolio" class="container-fluid text-center bg-grey">
    <!---->
    <h2>Portfolio</h2><br>
    <h4>Some of the things that we have built</h4>
    <div class="row text-center slideanim">
        <div class="col-sm-4">
            <div class="thumbnail">
                <img src="screenshots/sustainable-commerce__nc.png"
                     alt="Ethical &amp; sustainable ecommerce, HTML, CSS, Javascript &amp; PHP" width="400" height="300">
                <p>Ethical &amp; sustainable ecommerce</p>
                <p><strong>HTML, CSS &amp; PHP</strong></p>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="thumbnail">
                <img src="screenshots/ethical-ai__fnf.png"
                     alt="Predictive search, AI, Javascript, Elasticsearch &amp; PHP" width="400" height="300">
                <p>Predictive search &amp; AI</p>
                <p><strong>Javascript, Elasticsearch &amp; PHP</strong></p>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="thumbnail">
                <img src="screenshots/ethical-commerce__son.png"
                     alt="Responsive &amp; Adaptive HTML" width="400" height="300">
                <p>Responsive &amp; Adaptive HTML / Design</p>
                <p><strong>CSS2, CSS3 & HTML5</strong></p>  
            </div>
        </div>
    </div>
    <!-- -->

    <h2>What our customers say</h2>
    <div id="myCarousel" class="carousel slide text-center" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <div class="item active">
                <h4>
                    "This website &amp; company could really go far"<br>
                    <span>Neil Mcaffer, ex tennis pro &amp; coach</span>
                </h4>
            </div>
            <div class="item">
                <h4>
                    "I can't find the words necessary for this silliness"<br>
                    <span>Aaron Henderson, webmaster &amp; architect</span>
                    <!-- satire is always subliminal.. -->
                </h4>
            </div>
            <div class="item">
                <h4>
                    "I can't find the words necessary for this silliness"<br>
                    <span>Colm Rooney, Qigong &amp; Acupressure practitioner</span>
                    <!-- satire is always subliminal.. -->
                </h4>
            </div>
        </div>

        <!-- Left and right controls -->
        <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>

<!-- Container (Pricing Section) -->
<div id="services" class="container-fluid">
    <h2 class="text-center">We offer efficiency in the following</h2>
    <br>
    <div class="row slideanim">
        <div class="col-md-4 col-xs-12">
            <div class="panel panel-default text-center">
                <div class="panel-heading">
                    <h1>Discuss</h1>
                </div>
                <div class="panel-body text-left">
                    <p>
                        Enquire upon any of the following:
                    </p>
                    <p><span class="glyphicon glyphicon-ok"></span>&nbsp; Responsive &amp; Adaptive design</p>
                    <p><span class="glyphicon glyphicon-ok"></span>&nbsp; W3C Compliant HTML / Markup &amp; CSS</p>
                    <p><span class="glyphicon glyphicon-ok"></span>&nbsp; Website optimization(s)</p>
                    <p><span class="glyphicon glyphicon-ok"></span>&nbsp; SEO, SMO, A11Y &amp; Accessibility.</p>
                    <p><span class="glyphicon glyphicon-ok"></span>&nbsp; Copyright &amp; Content Writing.</p>
                    <p><span class="glyphicon glyphicon-ok"></span>&nbsp; Ethics and susainability.</p>
                    <p><span class="glyphicon glyphicon-ok"></span>&nbsp; Google Analytics, Datastudio &amp; Adwords.</p>
                    <p><span class="glyphicon glyphicon-ok"></span>&nbsp; Web application &amp; Cyber security.</p>

                </div>
                <div class="panel-footer">
                    <?PHP
                    /**
                    <h3>&pound;129.00</h3>
                    <h4>per hour</h4>
                     **/
                    ?>
                    <a href="https://forms.gle/8JKpWWdJxQPcS1jm7"
                       target="_blank" class="btn btn-lg">
                        Check Availability
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xs-12">
            <div class="panel panel-default text-center">
                <div class="panel-heading">
                    <h1>Development</h1>
                </div>
                <div class="panel-body text-left">
                    <p>Bespoke development engineered in the following:</p>
                    <p><span class="glyphicon glyphicon-ok"></span>&nbsp; Responsive &amp; Adaptive design</p>
                    <p><span class="glyphicon glyphicon-ok"></span>&nbsp; W3C Compliant HTML / Markup &amp; CSS</p>
                    <p><span class="glyphicon glyphicon-ok"></span>&nbsp; Website optimization(s)</p>
                    <p><span class="glyphicon glyphicon-ok"></span>&nbsp; SEO, SMO, A11Y &amp; Accessibility.</p>
                    <p><span class="glyphicon glyphicon-ok"></span>&nbsp; Machine Learning, AI &amp; Elasticsearch</p>
                    <p><span class="glyphicon glyphicon-ok"></span>&nbsp; API &amp; Payment Gateway integration(s)</p>
                    <p><span class="glyphicon glyphicon-ok"></span>&nbsp; Analytics, stayistics &amp; user testing.</p>
                    <p>&nbsp;</p>
                </div>
                <div class="panel-footer">
                    <?PHP
                    /**
                    <h3>&pound;129.00</h3>
                    <h4>per hour</h4>
                     **/
                    ?>
                    <a href="https://forms.gle/8JKpWWdJxQPcS1jm7"
                       target="_blank" class="btn btn-lg">
                        Check Availability
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xs-12">
            <div class="panel panel-default text-center">
                <div class="panel-heading">
                    <h1>Website</h1>
                </div>
                <div class="panel-body text-left">
                    <p>A custom built web page from us includes:</p>
                    <p><span class="glyphicon glyphicon-ok"></span>&nbsp; Responsive &amp; Adaptive Design / Markup</p>
                    <p><span class="glyphicon glyphicon-ok"></span>&nbsp; Unlimited Revisions / Changes</p>
                    <p><span class="glyphicon glyphicon-ok"></span>&nbsp; Basic search engine submission</p>
                    <p><span class="glyphicon glyphicon-ok"></span>&nbsp; Secure and reliable hosting.</p>
                    <p><span class="glyphicon glyphicon-ok"></span>&nbsp; Your content / product published right.</p>

                    <p><span class="glyphicon glyphicon-ok"></span>&nbsp; Lifetime warranty &amp; support.</p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                </div>
                <div class="panel-footer">
                    <?PHP
                    /**
                    <h3>&pound;129.00</h3>
                    <h4>per hour</h4>
                     **/
                    ?>
                    <a href="https://forms.gle/8JKpWWdJxQPcS1jm7"
                       target="_blank" class="btn btn-lg">
                        Check Availability
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Container (Contact Section) -->
<div id="contact" class="container-fluid bg-grey">
    <h2 class="text-left">CONTACT US</h2>
    <form class="row" action="/php/website/examples/henda.co.uk/" method="post">
        <div class="col-sm-5">
            <p>Contact us and we'll get back to you within 24 hours (hopefully).</p>
            <p><span class="glyphicon glyphicon-map-marker"></span> &nbsp;South Shields, Tyne and Wear, England, UK</p>
            <p><span class="glyphicon glyphicon-phone"></span> &nbsp;+44 7500 529 830</p>
            <p><span class="glyphicon glyphicon-envelope"></span> &nbsp;hello@aaronhenderson.co.uk</p>
        </div>
        <div class="col-sm-7 slideanim">
            <div class="row">
                <div class="col-sm-6 form-group form-group-lg">
                    <input class="form-control"
                           value="<?=form_input('name', '');?>"
                           id="name" name="name" placeholder="Name" type="text" required>
                </div>
                <div class="col-sm-6 form-group form-group-lg">
                    <input class="form-control"
                           value="<?=form_input('email', '');?>"
                           id="email" name="email" placeholder="Email" type="email" required>
                </div>
            </div>
            <div class="form-group form-group-lg">
                <textarea class="form-control"
                          id="comments" name="comments" placeholder="Your message"
                          rows="5"><?=form_input('comments', '');?></textarea>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group form-group-lg">
                    <button class="btn btn-primary btn-lg pull-right" type="submit">Send</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Image of location/map --><!--
<img src="http://http.localhost.com/home/uk_henda/photographs/saint-antonys.jpg" class="w3-image w3-greyscale-min" style="width:100%">
                                -->
<footer class="container-fluid text-center">
    <div class="row">
        <div class="col-sm-6 text-left">
            <br>
            <br>
            <br>
            <a href="#myPage" title="To Top">
                Back to the top of the page
                <!--  <span class="glyphicon glyphicon-chevron-up"></span> -->
            </a>
        </div>
        <div class="col-sm-6 text-right">
            <p>
                <img src="validator/images/support-valid-xhtml.png"
                     alt="I heart valid html, css and javascript.. always.">
            </p>
            <p>
                <a href="http://jigsaw.w3.org/css-validator/validator?uri=https://henda.co.uk/php/website/examples/henda.co.uk/"
                   target="_blank">
                    <img src="validator/images/valid-css.png"
                         alt="Valid CSS">
                </a>

                <a href="http://validator.w3.org/check?uri=https://henda.co.uk/php/website/examples/henda.co.uk/"
                   target="_blank">
                    <img src="validator/images/valid-xhtml10.png"
                         alt="Valid HTML">
                </a>
            </p>
            <p>
                Bootstrap theme made by
                <a href="https://www.w3schools.com" target="_blank" title="Visit w3schools">w3schools</a>
                &amp;
                <a href="https://www.aaronhenderson.co.uk" target="_blank" title="Visit henda's personal website">henda</a>
            </p>
        </div>
    </div>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        // Add smooth scrolling to all links in navbar + footer link
        $(".navbar a, footer a[href='#myPage'], #about a").on('click', function(event) {
            // Make sure this.hash has a value before overriding default behavior
            if (this.hash !== "") {
                // Prevent default anchor click behavior
                event.preventDefault();

                // Store hash
                var hash = this.hash;

                // Using jQuery's animate() method to add smooth page scroll
                // The optional number (900) specifies the number of milliseconds it takes to scroll to the specified area
                $('html, body').animate({
                    scrollTop: $(hash).offset().top
                }, 900, function(){

                    // Add hash (#) to URL when done scrolling (default click behavior)
                    window.location.hash = hash;
                });
            } // End if
        });

        // obviously comments regarding the following are witheld
        $(".slideanim").each(function(){
            var pos = $(this).offset().top;

            var winTop = $(window).scrollTop();
            if (pos < winTop + 600) {
                $(this).addClass("slide");
            }
        });

        $(window).scroll(function() {
            $(".slideanim").each(function(){
                var pos = $(this).offset().top;

                var winTop = $(window).scrollTop();
                if (pos < winTop + 600) {
                    $(this).addClass("slide");
                }
            });
        });

        $('#newsletter__subscribe').click(function (e) {
            e.preventDefault();
            $.post("<?=$_SERVER['REQUEST_URI'];?>", {
                input__email_address: $('#input__email_address').value
            });

            $('html, body').animate({
                scrollTop: $('#about').offset().top
            }, 900, function(){
                window.location.hash = '#about';
            });
        })
    })
</script>

</body>
</html>