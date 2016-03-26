<?php

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
        'components/bootstrap/css/bootstrap.css',
        'components/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
        'components/font-awesome/css/font-awesome.css',
    ];

    /**
     * List all js vendors
     * @var array
     */
    protected $vendorsJs = [
        'components/jquery/jquery.js',
        'components/moment/locale/pt-br.js',
        'components/moment/moment.js',
        'components/bootstrap/js/bootstrap.js',
        'components/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
    ];

    /**
     * List all fonts vendors
     * @var array
     */
    protected $vendorsFonts = [
        'components/bootstrap/fonts',
        'components/font-awesome/fonts',
    ];

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
        $this->_cleanDir("$this->distPath/js");
    }

    public function clearImg()
    {
        $this->_cleanDir("$this->distPath/img");
    }

    public function distCss()
    {
        $this->taskScss([
            "{$this->assetsPath}/css/main.scss" => "{$this->distPath}/css/{$this->projectName}.css",
        ])
            ->importDir("{$this->assetsPath}/css/*")
            ->run();

        $this->taskMinify("{$this->distPath}/css/{$this->projectName}.css")->run();

        $this->taskConcat($this->vendorsCss)
            ->to("{$this->distPath}/css/{$this->projectName}-vendors.css")
            ->run();
        $this->taskMinify("{$this->distPath}/css/{$this->projectName}-vendors.css")->run();
    }

    public function distFont()
    {
        $this->_copyDir(["{$this->assetsPath}/fonts"] + $this->vendorsFonts, "{$this->distPath}/fonts");
    }

    public function distJs()
    {
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
            ->monitor('public/css', function () {
                $this->distCss();
            })->run();
    }

    public function watchFont()
    {
        $this->taskWatch()
            ->monitor('public/font', function () {
                $this->distFont();
            })->run();
    }

    public function watchImg()
    {
        $this->taskWatch()
            ->monitor('public/img', function () {
                $this->distImg();
            })->run();
    }

    public function watchJs()
    {
        $this->taskWatch()
            ->monitor('public/js', function () {
                $this->distJs();
            })->run();
    }

    public function clear()
    {
        $this->clearCss();
        $this->clearFont();
        $this->clearJs();
        $this->clearImg();
    }

    public function dist()
    {
        $this->distCss();
        $this->distFont();
        $this->distJs();
        $this->distImg();
    }

    public function watch()
    {
        $this->taskWatch()
            ->monitor('composer.json', function () {
                $this->taskComposerUpdate()->preferDist()->run();
            })
            ->monitor('public/css', function () {
                $this->distCss();
            })
            ->monitor('public/font', function () {
                $this->distFont();
            })
            ->monitor('public/img', function () {
                $this->distImg();
            })
            ->monitor('public/js', function () {
                $this->distJs();
            })
            ->run();
    }

}