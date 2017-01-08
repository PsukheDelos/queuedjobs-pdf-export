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

    public function setup(){
        parent::setup();
        /**
         * Clear the styling requirements.
         * Because we are launching the job from the CMS, the Controller will attempt to use the CMS styling
         * instead of the website's selected theme.
         * By clearing the requirements, we remove the CMS styling.
         **/
        Requirements::clear();
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



        Config::inst()->update('SSViewer', 'theme_enabled', true);

        // Iterate through pages and generate PDFs
        $this->pages->limit(10)->each(function ($page) { //limiting to 10 pages while testing

            $page_controller = ModelAsController::controller_for($page);
            $page_controller->generatePDF();

        });

        $this->isComplete = true;
        return;
    }
}
