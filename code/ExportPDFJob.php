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

            /**
             * Clear the styling requirements.
             * Because we are launching the job from the CMS, the Controller will attempt to use the CMS styling
             * instead of the website's selected theme.
             * By clearing the requirements, we remove the CMS styling.
             **/
            Requirements::clear();
            Requirements::clear_combined_files();

            $page_controller =  ModelAsController::controller_for($page)->generatePDF();
            $page_controller->generatePDF();

        });

        $this->isComplete = true;
        return;
    }
}
