<?php
/**
 * This queued job is to export data into a CSV for downloading.
 *
 * Requires that pdf_export is enabled. For example, you may include in your config.yml:
 *
 * BasePage:
 *   pdf_export: 1
 *
 * Currently, only works with themes that are self contained (e.g. in one folder).
 * For instance, the default CWP theme does not work as it relies on a separate folder for bootstrap styling.
 */
class ExportPDFJob extends AbstractQueuedJob implements QueuedJob
{

    //Export PDF Variables
    protected $pages;
    protected $pageID;
    protected $batchSize;

    /**
     * Constructor
     */
    public function __construct($batchSize = 5, $pageID = 0) {
        //Get all Live Pages
        $this->pageID = $pageID;
        $this->batchSize = $batchSize;
        $this->pages = Versioned::get_by_stage('Page', 'Live')->where("\"SiteTree_Live\".\"ID\" > ".$pageID)->sort('ID ASC')->limit($this->batchSize);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'Export all pages to PDF';
    }

    /**
     * Setup this queued job. This is only called the first time this job is executed
     * (ie when currentStep is 0)
     */
    public function setup()
    {
        parent::setup();

        // Start from beginning of pages
        $this->currentStep = 0;
        $this->totalSteps = $this->pages->count();

    }

    /**
     * Process export to PDF
     */
    public function process()
    {
        //this is one possible albeit hacky solution, would still need a work around for home page
        //        $this->pages->each(function ($page) {
        //            Director::test($page->AbsoluteLink() . 'downloadpdf');
        //        });


        // If dataList / fields has not been populated as part of setup then exit.
//        if(empty($this->pages) || $this->totalSteps <= 0) {
//            $this->addMessage("No Live Pages found", 'ERROR');
//            $this->isComplete = true;
//            return;
//        }

        Config::inst()->update('SSViewer', 'theme_enabled', true);

        // Iterate through pages and generate PDFs
        $this->pages->each(function ($page) {

            /**
             * Clear the styling requirements.
             * Because we are launching the job from the CMS, the Controller will attempt to use the CMS styling
             * instead of the website's selected theme.
             * By clearing the requirements, we remove the CMS styling.
             **/
            Requirements::clear();
            Requirements::clear_combined_files();

            $page_controller = ModelAsController::controller_for($page);
            $page_controller->generatePDF();

            $this->currentStep++;
            $this->pageID = $page->id;
        });


            $export = new ExportPDFJob($this->batchSize, $this->pageID);
            singleton('QueuedJobService')->queueJob($export, time());

            $this->isComplete = true;
            $this->jobFinished();
    }
}
