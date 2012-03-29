<?php
require_once '../../../../oc-load.php';

//require_once('FrontendTest.php');


class OCadmin_languages extends OCadminTest {
    
    private $canUpload = TRUE;
    private $canUpload_;
    
    /*
     * Login oc-admin
     * Check if can upload languages
     */
    function testPreUpload()
    {
        
        @chmod(CONTENT_PATH."uploads/", 0777);
        @chmod(CONTENT_PATH."languages/", 0777);
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Languages");
        $this->selenium->click("link=Add a language");
        $this->selenium->waitForPageToLoad("10000");

        if( $this->selenium->isTextPresent('To make the directory writable') ) {
            $this->assertFalse(TRUE,"DIRECTORY TO UPLOAD LANGUAGES ISN'T WRITABLE") ;
            $this->canUpload_ = FALSE;
        }else{
            $this->canUpload_ = TRUE;
        }
    }
    
    /*
     * Login oc-admin
     * Insert new language
     * Delete new language
     */
    function testInsertLanguage()
    {    
        if($this->canUpload){
            $this->loginWith();
            // insert language
            $this->selenium->open( osc_admin_base_url(true) ) ;
            $this->selenium->click("link=Languages");
            $this->selenium->click("link=Add a language");
            $this->selenium->waitForPageToLoad("10000");
            $this->selenium->type("package", $this->selenium->_path(LIB_PATH."simpletest/test/osclass/lang_es_ES_2.0.zip"));
            $this->selenium->click("//input[@type='submit']");
            $this->selenium->waitForPageToLoad("10000");
            $this->assertTrue($this->selenium->isTextPresent("The language has been installed correctly"),"Upload new language lang_es_ES_2.0.zip");
            $this->deleteLanguage();
        }
    }
    
    /*
     * Login oc-admin
     * Insert new language, via link at manageLanguages
     */
    function testLanguageInsertbyLink()
    {
        if($this->canUpload){
            $this->loginWith();
            // insert by link 
            $this->selenium->open( osc_admin_base_url(true) ) ;
            $this->selenium->click("link=Languages");
            $this->selenium->click("link=Manage languages");
            $this->selenium->waitForPageToLoad("10000");
            $this->selenium->click("link=Add language");
            $this->selenium->waitForPageToLoad("10000");
            $this->selenium->type("package", $this->selenium->_path(LIB_PATH."simpletest/test/osclass/lang_es_ES_2.0.zip"));
            $this->selenium->click("//input[@type='submit']");
            $this->selenium->waitForPageToLoad("10000");
            $this->assertTrue($this->selenium->isTextPresent("The language has been installed correctly"),"Upload new language lang_es_ES_2.0.zip");
        }
    }
    
    /*
     * Login oc-admin
     * Play with enable/disble front/back end.
     */
    function testEnableDisable()
    {
        if($this->canUpload){
            $this->loginWith();
            if( $this->isDisabledWebsite("Spanish") ){
                $this->enableWebsite("Spanish");
                $this->checkWebsiteEnabled("Spanish");
                $this->disableWebsite("Spanish");
                $this->checkWebsiteDisabled("Spanish");
            } else {
                $this->disableWebsite("Spanish");
                $this->checkWebsiteDisabled("Spanish");
                $this->enableWebsite("Spanish");
                $this->checkWebsiteEnabled("Spanish");
            }

            if( $this->isDisabledOCAdmin("Spanish") ) {
                $this->enableOCAdmin("Spanish");
                $this->logout();
                $this->checkOCAdminEnabled("Spanish");
                
                $this->loginWith();
                $this->disableOCAdmin("Spanish");
                $this->logout();
                $this->checkOCAdminDisabled("Spanish");
                $this->loginWith() ;
            } else {
                $this->disableOCAdmin("Spanish");
                $this->logout();
                $this->checkOCAdminDisabled("Spanish");
                $this->loginWith();
                $this->enableOCAdmin("Spanish");
                $this->logout();
                $this->checkOCAdminEnabled("Spanish");
                $this->loginWith();
            }
        }
    }
    
    public function testLanguageEdit()
    {
        if($this->canUpload){
            $this->loginWith() ;
            $this->selenium->open( osc_admin_base_url(true) );
            $this->selenium->click("link=Languages");
            $this->selenium->click("link=Manage languages");
            $this->selenium->waitForPageToLoad("10000");
            $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Spanish')]");
            $this->selenium->click("//table/tbody/tr/td[contains(.,'Spanish')]/div/div/a[text()='Edit']");
            $this->selenium->type("s_name","Spanish upadated");
            $this->selenium->type("s_short_name","Spanish upadated");
            $this->selenium->type("s_description","Spanish translation updated");
            $this->selenium->type("s_stop_words","foo,bar");
            $this->selenium->click("b_enabled");
            $this->selenium->click("//input[@type='submit']");
            $this->selenium->waitForPageToLoad("10000");
            $this->assertTrue($this->selenium->isTextPresent("Spanish upadated has been updated"),"Edit language Spanish");
        }
    }

    // We should not delete the language or we'll broke the installer with various locales
    /*public function testDeleteLanguage()
    {
        if($this->canUpload){
            $this->loginWith();
            $this->deleteLanguage();
        }
    }*/
    
    
    
    
    // private functions
    private function doAction($action, $lang = "Spanish")
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Languages");
        $this->selenium->click("link=Manage languages");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("//table/tbody/tr/td[contains(.,'$lang')]/div/div/a[contains(.,'$action')]");
        $this->selenium->waitForPageToLoad("10000");
    }
    
    
    
    // private functions
    private function deleteLanguage($lang = "Spanish")
    {
        $this->doAction("Delete");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("has been successfully removed"),"Can't delete language Spanish");
    }
    
    private function isDisabledOCAdmin($lang)
    {
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Languages");
        $this->selenium->click("link=Manage languages");
        $this->selenium->waitForPageToLoad("10000");

        $thereare = $this->selenium->getXpathCount("//table/tbody/tr/td[contains(.,'$lang')]/div/div/a[text()='Disable (oc-admin)']");
        if($thereare == 1) {
            return FALSE;
        }else{
            return TRUE;
        }
    }

    private function isDisabledWebsite($lang)
    {
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Languages");
        $this->selenium->click("link=Manage languages");
        $this->selenium->waitForPageToLoad("10000");

        $thereare = $this->selenium->getXpathCount("//table/tbody/tr/td[contains(.,'$lang')]/div/div/a[text()='Disable (website)']");
        if($thereare == 1) {
            return FALSE;
        }else{
            return TRUE;
        }
    }

    private function enableWebsite($lang)
    {
        $this->doAction("Enable (website)", $lang);
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Selected languages have been enabled for the website"),"Can't enable (website) language $lang");
    }

    private function checkWebsiteEnabled($lang)
    {
        $this->selenium->open( osc_base_url(true) ) ;
        // position cursor on language
        $this->selenium->mouseMove("xpath=//strong[text()='Language']");
        $this->assertTrue($this->selenium->isTextPresent("$lang"),"The language has not been activated correctly (website language $lang)");

        $this->selenium->click("link=$lang");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Idioma"),"Can't find $lang strings (website language $lang)");
    }

    private function disableWebsite($lang)
    {
        $this->doAction("Disable (website)", $lang);
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Selected languages have been disabled for the website"),"Can't disable (website) language $lang");
    }

    private function checkWebsiteDisabled($lang)
    {
        $this->assertTrue($this->selenium->isTextPresent("Language"),"There are more than en_US language at website");
    }

    private function enableOCAdmin($lang)
    {
        $this->doAction("Enable (oc-admin)", $lang);
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Selected languages have been enabled for the backoffice (oc-admin)"),"Can't enable (backoffice) language $lang");
    }

    private function checkOCAdminEnabled($lang)
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $language = $this->selenium->isTextPresent('Language') ;
        if( $language ){
            $this->selenium->select('id=user_language', "$lang") ;
            $this->selenium->type('user', 'testadmin');
            $this->selenium->type('password', 'password');
            sleep(10);
            $this->selenium->click('submit');
            $this->selenium->waitForPageToLoad(1000);

            //if( $this->selenium->isTextPresent('Desconectar') ) {
            //    $this->selenium->click('Desconectar');
            if( $this->selenium->isTextPresent('Sign out') ) {
                $this->selenium->click('Sign out');
                $this->assertTrue(TRUE);
            } else {
                $this->selenium->click('Sign out');
                $this->assertTrue(FALSE, "The language has not been activated correctly OCAdmin $lang");
            }
            $this->selenium->waitForPageToLoad(1000);
        } else {
            $this->assertTrue(TRUE,'There aren\'t selector of language at OCAdmin' );
        }
    }

    private function disableOCAdmin($lang)
    {
        $this->doAction("Disable (oc-admin)", $lang);
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Selected languages have been disabled for the backoffice (oc-admin)"),"Can't disable (backoffice) language $lang");
    }

    private function checkOCAdminDisabled($lang)
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->assertTrue(!$this->selenium->isTextPresent('Language'), "There are more than en_US language at OCAdmin");
    }
}
?>