<?php
/**
 * This queued job is to export data into a CSV for downloading.
 *
 */
class ExportPDFJob extends AbstractQueuedJob implements QueuedJob {

    //Export PDF Variables
    protected $pages;

    /**
     * Constructor
     */
    public function __construct() {
        //$this->pages =  Page::get();
        $this->pages = Versioned::get_by_stage('Page', 'Live');
        $this->pages2 = Page::get();
    }

    /**
     * @return string
     */
    public function getTitle() {
        return 'Export all pages to PDF';
    }

    /**
     * Process export to PDF
     */
    public function process() {
//        var_dump($this->pages->count());

//        var_dump($this->pages->first()->getClassName());

        $this->pages->each(function($obj){

//            var_dump(Config::inst());
//            die;

//            var_dump($obj);
//SSViewer::set_theme('d');


//            Config::inst()->update('SSViewer', 'theme', 'default');
//            var_dump(Config::inst()->get('SSViewer', 'theme'));
            var_dump($obj->ClassName);


//            die;



//            SSViewer::set_theme('default');
//            $obj->RenderWith('Page');
//            ModelAsController::controller_for($obj)->generatePDF();
// $this->
//            $ch = curl_init();
//
//            // set URL and other appropriate options
//            curl_setopt($ch, CURLOPT_URL, $obj->AbsoluteLink() . 'downloadpdf');
////            var_dump($obj->AbsoluteLink() . 'downloadpdf'); die;
//            curl_setopt($ch, CURLOPT_HEADER, 0);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
////            file_get_contents($obj->AbsoluteLink() . 'downloadpdf');
//
//            // grab URL and pass it to the browser
//            curl_exec($ch);
//
//            // close cURL resource, and free up system resources
//                        curl_close($ch);

//            Director::getControllerForURL($obj->AbsoluteLink())->generatePDF();
//            ModelAsController::controller_for($obj)->generatePDF();
//            die;
//            var_dump(SSViewer::current_theme()); die;
//            SSViewer::set_theme('default');
//            ModelAsController::controller_for($obj)->generatePDF();
//            var_dump('test'); die;
//            $obj = $obj->renderWith('nmit');
//            var_dump($obj); die;
//            $obj = ModelAsController::controller_for($obj);
//            $obj->generatePDF();
//            $obj2 = new BasePage_Controller($obj);
//            $obj2->generatePDF();
//            var_dump
//            var_dump("hi"); die;
//            $obj->genPDF();
//            die("hi");

//            var_dump($obj->getRawFAQCategories()); die;
//            var_dump("process"); die;
//            Debug::dump($obj); die;
//            var_dump(ModelAsController::controller_for($obj,'generatePDF'));die;
//            Director::test($obj->);
//            var_dump($obj->AbsoluteLink() . 'downloadpdf'); die;
//            Director::
//            var_dump(Director::test($obj->AbsoluteLink() . 'downloadpdf'));
//            ModelAsController::controller_for($obj)->dataRecord->getPdfFilename();

//            var_dump($obj->getController());
//            $path = ModelAsController::controller_for($obj)->dataRecord->getPdfFilename();
//            if(!file_exists($path)) {
//
//                ModelAsController::controller_for($obj,'generatePDF');
////                $obj->generatePDF();
//            }
//            ModelAsController::controller_for($obj)->generatePDF();

            //create jobs that create follow on jobs
            //create another job that does next 100
            //reason to separate out the feature

        });

        $this->isComplete = true;
        return;
    }

}