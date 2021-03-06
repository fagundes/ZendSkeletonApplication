<?php

require 'config/application.config.php';

/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{

    /**
     * Project Name is used to create dist css and js files
     * @var string
     */
    protected $projectName = PROJECT_NAME;

    protected $urlDev  = 'http://localhost';
    protected $urlProd = 'http://example.com';

    /**
     * Assets Path is the default folder to assets in development
     * @var string
     */
    protected $assetsPath = 'public';

    /**
     * Dist Path is the default folder where it will generated the full compiled assets
     * @var string
     */
    protected $distPath = 'public/dist';

    /**
     * List all css vendors
     * @var array
     */
    protected $vendorsCss = [

    ];

    /**
     * List all js vendors
     * @var array
     */
    protected $vendorsJs = [

    ];

    /**
     * List all fonts vendors
     * @var array
     */
    protected $vendorsFonts = [

    ];

    public function clearCache()
    {
        $this->_cleanDir("data/cache");
    }

    public function clearCss()
    {
        $this->_cleanDir("$this->distPath/css");
    }

    public function clearFont()
    {
        $this->_cleanDir("$this->distPath/fonts");
    }

    public function clearJs()
    {
        $ignoreJsDistFiles = [
            'respond.min.js',
            'html5shiv.min.js',
        ];

        $data = $this->taskTmpDir()->run()->getData();
        $tmp  = $data['path'];

        foreach ($ignoreJsDistFiles as $file) {
            $this->_rename("$this->distPath/js/$file", "$tmp/$file");
        }

        $this->_cleanDir("$this->distPath/js");

        foreach ($ignoreJsDistFiles as $file) {
            $this->_rename("$tmp/$file", "$this->distPath/js/$file");
        }
    }

    public function clearImg()
    {
        $this->_cleanDir("$this->distPath/img");
    }

    public function distCss()
    {
        $this->clearCss();

        $this->taskScss([
            "{$this->assetsPath}/css/main.scss" => "{$this->distPath}/css/{$this->projectName}.css",
        ])
            ->importDir("{$this->assetsPath}/css/")
            ->run();

        $this->taskMinify("{$this->distPath}/css/{$this->projectName}.css")->run();

        $this->taskConcat($this->vendorsCss)
            ->to("{$this->distPath}/css/{$this->projectName}-vendors.css")
            ->run();
        $this->taskMinify("{$this->distPath}/css/{$this->projectName}-vendors.css")->run();
    }

    public function distFont()
    {
        $this->clearFont();

        $fromDirs = array_merge(["{$this->assetsPath}/fonts/*{.eot,.svg,.ttf,.woff,.woff2}"], $this->vendorsFonts);
        $dirs     = array_combine($fromDirs, array_fill(0, count($fromDirs), "{$this->distPath}/fonts"));

        $this->taskFlattenDir($dirs)->run();
    }

    public function distJs()
    {
        $this->clearJs();

        $this->taskConcat(["{$this->assetsPath}/js/**.js"])
            ->to("{$this->distPath}/js/{$this->projectName}.js")
            ->run();

        $this->taskMinify("{$this->distPath}/js/{$this->projectName}.js")->run();

        $this->taskConcat($this->vendorsJs)
            ->to("{$this->distPath}/js/{$this->projectName}-vendors.js")
            ->run();
        $this->taskMinify("{$this->distPath}/js/{$this->projectName}-vendors.js")->run();
    }

    public function distImg()
    {
        $this->clearImg();

        $this->taskFlattenDir(["{$this->assetsPath}/img/*.ico" => "{$this->distPath}/img"])->run();

        $this->taskImageMinify("{$this->assetsPath}/img/*{.png,.gif,.svg}")
            ->to("{$this->distPath}/img/")
            ->run();

        $this->taskImageMinify("{$this->assetsPath}/img/*.jpg")
            ->to("{$this->distPath}/img/")
            ->minifier('jpeg-recompress', ['--quality' => 'low'])
            ->run();
    }

    /**
     * Open a browser with url in $urlDev
     */
    public function launchDev()
    {
        $this->launch($this->urlDev);
    }

    /**
     * Open a browser with url in $urlProd
     */
    public function launchProd()
    {
        $this->launch($this->urlProd);
    }

    public function watchComposer()
    {
        // when composer.json changes `composer update` will be executed
        $this->taskWatch()
            ->monitor('composer.json', function () {
                $this->taskComposerUpdate()->preferDist()->run();
            })->run();
    }

    public function watchCss()
    {
        $this->taskWatch()
            ->monitor("{$this->assetsPath}/css", function () {
                $this->distCss();
            })->run();
    }

    public function watchFont()
    {
        $this->taskWatch()
            ->monitor("{$this->assetsPath}/fonts", function () {
                $this->distFont();
            })->run();
    }

    public function watchImg()
    {
        $this->taskWatch()
            ->monitor("{$this->assetsPath}/img", function () {
                $this->distImg();
            })->run();
    }

    public function watchJs()
    {
        $this->taskWatch()
            ->monitor("{$this->assetsPath}/js", function () {
                $this->distJs();
            })->run();
    }

    /**
     * Cleanup dist files
     */
    public function clear()
    {
        $this->clearCache();
        $this->clearCss();
        $this->clearFont();
        $this->clearJs();
        $this->clearImg();
    }

    /**
     * Concat, minify and write assets in dist folder
     */
    public function dist()
    {
        $this->distCss();
        $this->distFont();
        $this->distJs();
        $this->distImg();
    }

    /**
     * Watch assets folders and compopser.json file and run respectively `dist:folder` or `composer update`
     */
    public function watch()
    {
        $this->taskWatch()
            ->monitor('composer.json', function () {
                $this->taskComposerUpdate()->preferDist()->run();
            })
            ->monitor("{$this->assetsPath}/css", function () {
                $this->distCss();
            })
            ->monitor("{$this->assetsPath}/fonts", function () {
                $this->distFont();
            })
            ->monitor("{$this->assetsPath}/img", function () {
                $this->distImg();
            })
            ->monitor("{$this->assetsPath}/js", function () {
                $this->distJs();
            })
            ->run();
    }

    /**
     * Open a browser with the url passed
     *
     * @param string $url url to be opened on browser
     */
    public function launch($url)
    {
        // open one browser window
        $this->taskOpenBrowser($url)->run();
    }

}