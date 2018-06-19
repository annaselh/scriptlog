<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");
/**
 * PageApp Class extends BaseApp Class
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class PageApp extends BaseApp
{
    
 protected $view;
 
 public function __construct(PageEvent $pageEvent, FormValidator $validator)
 {
   $this->pageEvent = $pageEvent;
   $this->validator = $validator;
 }
 
 public function listItems()
 {
     
 }
 
 public function insert()
 {
     
 }
 
 public function update($id)
 {
     
 }
 
 public function delete($id)
 {
     
 }
 
}