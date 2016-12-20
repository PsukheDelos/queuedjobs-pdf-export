<?php
/**
 * This queued job is to export data into a CSV for downloading.
 *
 */
class ExportPDFJob extends AbstractQueuedJob implements QueuedJob
{

    //Export PDF Variables
    protected $pages;

    /**
     * Constructor
     */
    public function __construct()
    {
        //$this->pages =  Page::get();
        //Get all Live Pages
        $this->pages = Versioned::get_by_stage('Page', 'Live');
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'Export all pages to PDF';
    }

    /**
     * Process export to PDF
     */
    public function process()
    {
        //@TODO: Limit this to processing 100 pages, then create a new job to process next 100 until done.
        //this is one possible albeit hacky solution, would still need a work around for home page
//        $this->pages->each(function ($page) {
//            Director::test($page->AbsoluteLink() . 'downloadpdf');
//        });


        $oldEnabled = Config::inst()->get('SSViewer', 'theme_enabled');
        $oldTheme = Config::inst()->get('SSViewer', 'theme');

        Config::inst()->update('SSViewer', 'theme_enabled', true);
        Config::inst()->update('SSViewer', 'theme', 'new'); //experiment to manually set theme while processing pdf


        //This is main piece of code
        //--------------------------
        $this->pages->limit(1)->each(function ($page) { //limiting to 1 page while testing

            $page_controller = ModelAsController::controller_for($page);
            $page_controller->generatePDF();

        });
        //--------------------------

        Config::inst()->update('SSViewer', 'theme_enabled', $oldEnabled);
        Config::inst()->update('SSViewer', 'theme', $oldTheme);


        $this->isComplete = true;
        return;
    }
}
