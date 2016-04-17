<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->fetch('title') ?></title>

    <link rel="stylesheet" type="text/css" href="/css/style.css" media="all" />

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <?= $this->Flash->render() ?>

    <figure class="svg-library" style="display:none;">
        <?= $this->Svg->display('brand/logo') ?>
    </figure>

    <div class="app">

        <div class="g1">

        	<nav class="menu col">

        		<ol class="menu__list">

        			<li class="menu__list__item">
        				<a href="/">
    						<svg><use class="abcde" xlink:href="#brand-logo" /></svg>
    					</a>
        			</li>

        			<li class="menu__list__item">
        				<a href="/">Dashboard</a>
        			</li>

        			<li class="menu__list__item">
        				<a href="/">This Week</a>
        			</li>

        			<li class="menu__list__item">
        				<a href="/">Leaderboard</a>
        			</li>

        			<li class="menu__list__item">
        				<a href="/">Fixtures</a>
        			</li>

        			<li class="menu__list__item">
        				<a href="/">Players</a>
        			</li>

        			<li class="menu__list__item">
        				<a href="/">My Profile</a>
        			</li>

        		</ol>

        	</nav>

            <article class="view col">

                <div class="header g2">

                    <div class="col">

                        <h1>Dashboard</h1>

                    </div>

                    <div class="col">

                        <h1>Notifications</h1>

                    </div>

                </div>

                <article class="g2">

                    <section class="col">

                        <p><strong>Pellentesque habitant morbi tristique</strong> senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. <em>Aenean ultricies mi vitae est.</em> Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, <code>commodo vitae</code>, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. <a href="#">Donec non enim</a> in turpis pulvinar facilisis. Ut felis.</p>

                        <h2>Header Level 2</h2>
                                   
                        <ol>
                           <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
                           <li>Aliquam tincidunt mauris eu risus.</li>
                        </ol>

                        <blockquote><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at felis aliquet congue. Ut a est eget ligula molestie gravida. Curabitur massa. Donec eleifend, libero at sagittis mollis, tellus est malesuada tellus, at luctus turpis elit sit amet quam. Vivamus pretium ornare est.</p></blockquote>

                        <h3>Header Level 3</h3>

                        <ul>
                           <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
                           <li>Aliquam tincidunt mauris eu risus.</li>
                        </ul>

                        <pre><code>
                        #header h1 a { 
                            display: block; 
                            width: 300px; 
                            height: 80px; 
                        }
                        </code></pre><h1>HTML Ipsum Presents</h1>
                                   
                        <p><strong>Pellentesque habitant morbi tristique</strong> senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. <em>Aenean ultricies mi vitae est.</em> Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, <code>commodo vitae</code>, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. <a href="#">Donec non enim</a> in turpis pulvinar facilisis. Ut felis.</p>

                        <h2>Header Level 2</h2>
                                   
                        <ol>
                           <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
                           <li>Aliquam tincidunt mauris eu risus.</li>
                        </ol>

                    </section>

                    <section class="col">

                        <blockquote><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at felis aliquet congue. Ut a est eget ligula molestie gravida. Curabitur massa. Donec eleifend, libero at sagittis mollis, tellus est malesuada tellus, at luctus turpis elit sit amet quam. Vivamus pretium ornare est.</p></blockquote>

                        <h3>Header Level 3</h3>

                        <ul>
                           <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
                           <li>Aliquam tincidunt mauris eu risus.</li>
                        </ul>

                        <pre><code>
                        #header h1 a { 
                            display: block; 
                            width: 300px; 
                            height: 80px; 
                        }
                        </code></pre><h1>HTML Ipsum Presents</h1>
                                   
                        <p><strong>Pellentesque habitant morbi tristique</strong> senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. <em>Aenean ultricies mi vitae est.</em> Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, <code>commodo vitae</code>, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. <a href="#">Donec non enim</a> in turpis pulvinar facilisis. Ut felis.</p>

                        <h2>Header Level 2</h2>
                                   
                        <ol>
                           <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
                           <li>Aliquam tincidunt mauris eu risus.</li>
                        </ol>

                        <blockquote><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at felis aliquet congue. Ut a est eget ligula molestie gravida. Curabitur massa. Donec eleifend, libero at sagittis mollis, tellus est malesuada tellus, at luctus turpis elit sit amet quam. Vivamus pretium ornare est.</p></blockquote>

                        <h3>Header Level 3</h3>

                        <ul>
                           <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
                           <li>Aliquam tincidunt mauris eu risus.</li>
                        </ul>

                        <pre><code>
                        #header h1 a { 
                            display: block; 
                            width: 300px; 
                            height: 80px; 
                        }
                        </code></pre><h1>HTML Ipsum Presents</h1>
                                   
                        <p><strong>Pellentesque habitant morbi tristique</strong> senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. <em>Aenean ultricies mi vitae est.</em> Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, <code>commodo vitae</code>, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. <a href="#">Donec non enim</a> in turpis pulvinar facilisis. Ut felis.</p>

                        <h2>Header Level 2</h2>
                                   
                        <ol>
                           <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
                           <li>Aliquam tincidunt mauris eu risus.</li>
                        </ol>

                        <blockquote><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at felis aliquet congue. Ut a est eget ligula molestie gravida. Curabitur massa. Donec eleifend, libero at sagittis mollis, tellus est malesuada tellus, at luctus turpis elit sit amet quam. Vivamus pretium ornare est.</p></blockquote>

                        <h3>Header Level 3</h3>

                        <ul>
                           <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
                           <li>Aliquam tincidunt mauris eu risus.</li>
                        </ul>

                        <pre><code>
                        #header h1 a { 
                            display: block; 
                            width: 300px; 
                            height: 80px; 
                        }
                        </code></pre>

                    </section>

                </article>



                
           




        	    <?= $this->fetch('content') ?>

            </article>

        </div><?/* .g1 */?>

	</div>

</body>
</html>