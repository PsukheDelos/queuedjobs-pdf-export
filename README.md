# SilverStripe Queued Jobs PDF Export Module

## Maintainer Contact

Glen Peek

## Requirements

* CWP 
* SilverStripe Queued Jobs Module 2.x
* This module requires that pdf_export is enabled. For example, you may include in your config.yml:
   
  ```
  BasePage: 
    pdf_export: 1
         
  ```
   
* Currently, only works with themes that are self contained (e.g. in one folder). For instance, the default CWP theme does not work as it relies on a separate folder for bootstrap styling.

## Version info

The master branch of this module is currently aiming for SilverStripe 3.x compatibility

## Documentation

The Queued Jobs PDF Export module provides a process for exporting all Live Pages to PDF. 

By default, when PDF export is enabled on a CWP site, a download PDF link is added to all BasePages. 
The first time this link is hit, a PDF is generated of the page and then served to the user.
On subsequent requests, the already generated PDF is served to the user instead of regenerating the PDF. 

However, it was discovered that this was causing some CWP instances to overload. 
Bots crawling the web would happen upon a CWP site. They would then go about hitting every path they could. 
In doing so, they would hit the generatePDF function of every page in a short space of time. 
As many pages across the site had not had PDFs generated, this would cause the CWP instance to overload as it would now 
have to generate countless PDFs.

This was designed to prevent PDF Generation from overloading CWP instances by pushing generation of PDFs out to 
QueuedJobs to be run during off peak hours. This would ensure that all pages had a cached, pre-generated PDF available for download. 

## Future Work

At present, this module would be run every so often on off peak hours. 
Ideally, the Export PDF Job would be run once over a website to generate all PDFs initially.
BasePage could then be modified to published a new PDF every time it is updated / published.  
   

## Quick Usage Overview

* To run, select the ExportPDFJob from the Jobs tab of the CMS and run. Ideally, this should be done during off peak hours. 
 
 


