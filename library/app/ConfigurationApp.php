<?php
/**
 * ConfigurationApp Class extends BaseApp Class
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class ConfigurationApp extends BaseApp
{
  private $view;

  private $confiEvent;

  public function __construct(ConfigurationEvent $configEvent)
  {
    $this->configEvent = $configEvent;
  }

  public function ListItems()
  {
    $errors = array();
    $status = array();
    $checkError = true;
    $checkStatus = false;

    if(isset($_GET['error'])) {
      $checkError = false;
      if($_GET['error'] == 'configNotFound') array_push($errors, "Error: Setting Not Found!");
    }

    if (isset($_GET['status'])) {
       $checkStatus = true;
       if ($_GET['status'] == 'configUpdated') array_push($status, "Setting has been updated");
       if ($_GET['status'] == 'configDeleted') array_push($status, "Setting deleted");
    }

      $data_config = $this->configEvent->grabSettings();

      $this->setView('options-form');
      $this->setPageTitle('General Setting');
      
    if (empty($data_config['ID'])) {

      $this->setformAction('setConfig');

    } else {

      $this->setFormAction('editConfig');
      
    }

    if (!$checkError) {
      $this->view->set('errors', $errors);
    }

    if ($checkStatus) {
      $this->view->set('status', $status);
    }

    $this->view->set('pageTitle', $this->getPageTitle());
    $this->view->set('formAction', $this->getFormAction());
    $this->view->set('configData', $data_config);
    $this->view->set('csrfToken', csrf_generate_token('csrfToken'));

    return $this->view->render();

  }

  public function insert()
  {
    $errors = array();
    $checkError = true;

    if (isset($_POST['configFormSubmit'])) {
      
      $app_url = filter_input('INPUT_POST', 'app_url', FILTER_SANITIZE_URL);
      $site_title = filter_input('INPUT_POST', 'site_title', FILTER_SANITIZE_STRING);
      $meta_desc = prevent_injection($_POST['meta_description']);
      $meta_key = prevent_injection($_POST['meta_keywords']);
      $email_address = filter_input('INPUT_POST', 'email', FILTER_SANITIZE_EMAIL);
      $facebook = filter_input('INPUT_POST', 'facebook', FILTER_SANITIZE_URL);
      $twitter = filter_input('INPUT_POST', 'twitter', FILTER_SANITIZE_URL);
      $instagram = filter_input('INPUT_POST', 'instagram', FILTER_SANITIZE_URL);

      try {

        if (!csrf_check_token('csrfToken', $_POST, 60*10)) {
                
          header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
          throw new AppException("Sorry, unpleasant attempt detected!");

        }

        if (strlen($site_title) > 60) {

           $checkError = false;
           array_push($errors, 'Exceeded characters limit. Maximum 60 characters are allowed.');

        }

        if (strlen($meta_desc) > 300) {

           $checkError = false;
           array_push($errors, 'Exceeded characters limit. Maximum 300 characters are allowed.');

        }

        if (!$checkError) {

           $this->setView('options-form.php');
           $this->setPageTitle('General Setting');
           $this->setFormAction('setConfig');
           $this->view->set('pageTitle', $this->getPageTitle());
           $this->view->set('formAction', $this->getFormAction());
           $this->view->set('errors', $errors);
           $this->view->set('formData', $_POST);
           $this->view->set('csrfToken', csrf_generate_token('csrfToken'));

        } else {

          $this->configEvent->setAppUrl($app_url);
          $this->configEvent->setSiteName($site_title);
          $this->configEvent->setMetaDesc($meta_desc);
           
        }

      } catch (AppException $e) {

        LogError::setStatusCode(http_response_code());
        LogError::newMessage($e);
        LogError::customErrorMessage('admin');

      }

    } else {

      $this->setView('options-form');
      $this->setPageTitle('General Setting');
      $this->setFormAction('setConfig');
      $this->view->set('pageTitle', $this->getPageTitle());
      $this->view->set('formAction', $this->getFormAction());
      
    }

    return $this->view->render();

  }

  public function update($id)
  {
    $errors = array();
    $checkError = true;

    if (!$getSetting = $this->configEvent->grabSetting($id)) {

      direct_page('index.php?load=settings&error=configNotFound', 404);

    }

    $data_config = array(
      'ID' => $getSetting['ID'],
      'app_key' => $getSetting['app_key'], 
      'app_url' => $getSetting['app_url'],
      'site_name' => $getSetting['site_name'],
      'meta_description' => $getSetting['meta_description'],
      'meta_keywords' => $getSetting['meta_keywords'],
      'logo' => $getSetting['logo'],
      'email]_address' => $getSetting['email_address'],
      'facebook' => $getSetting['facebook'],
      'twitter' => $getSetting['twitter'],
      'instagram' => $getSetting['instagram']
    );

    if (isset($_POST['configFormSubmit'])) {

      $config_id = isset($_POST['config_id']) ? abs((int)$_POST['config_id']) : 0;
      $app_url = filter_input('INPUT_POST', 'app_url', FILTER_SANITIZE_URL);
      $site_title = filter_input('INPUT_POST', 'site_title', FILTER_SANITIZE_STRING);
      $meta_desc = prevent_injection($_POST['meta_description']);
      $meta_key = prevent_injection($_POST['meta_keywords']);
      $email_address = filter_input('INPUT_POST', 'email', FILTER_SANITIZE_EMAIL);
      $facebook = filter_input('INPUT_POST', 'facebook', FILTER_SANITIZE_URL);
      $twitter = filter_input('INPUT_POST', 'twitter', FILTER_SANITIZE_URL);
      $instagram = filter_input('INPUT_POST', 'instagram', FILTER_SANITIZE_URL);

      try {

        if (!csrf_check_token('csrfToken', $_POST, 60*10)) {
                
          header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
          throw new AppException("Sorry, unpleasant attempt detected!");

        }

        if (!$checkError) {

          $this->setView('options-form');
          $this->setPageTitle('General Setting');
          $this->setFormAction('editConfig');
          $this->view->set('pageTitle', $this->getPageTitle());
          $this->view->set('formAction', $this->getFormAction());
          $this->view->set('errors', $errors);
          $this->view->set('configData', $data_config);
          $this->view->set('csrfToken', csrf_generate_token('csrfToken'));

        } else {

          $this->configEvent->setConfigId($config_id);
          $this->configEvent->setAppUrl($app_url);
          $this->configEvent->setSiteName($site_title);
          $this->configEvent->setMetaDesc($meta_desc);
          $this->configEvent->setMetaKey($meta_key);
          $this->configEvent->setEmailAddress($email_address);
          $this->configEvent->setFacebook($facebook);
          $this->configEvent->setTwitter($twitter);
          $this->configEvent->setInstagram($instagram);
          $this->configEvent->modifySetting();
          direct_page('index.php?load=settings&status=configUpdated', 200);

        }

      } catch (AppException $e) {

         LogError::setStatusCode(http_response_code());
         LogError::newMessage($e);
         LogError::customErrorMessage('admin');

      }

    } 

    return $this->view->render();

  }

  public function delete($id)
  {

  }

  protected function setView($viewName)
  {
    $this->view = new View('admin', 'ui', 'setting', $viewName);
  }

}