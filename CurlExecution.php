<?php

/**
 * Created by PhpStorm.
 * User: Alessandra Zullo
 * Date: 06/02/2016
 * Time: 12.00
 */
class CurlExecution
{
    public $packtPage;
    function CurlExecution($url){

        $this->packtPage = $this->curl($url);
    }

    public function curl($url) {
        try {
            $ch = curl_init();

            if (FALSE === $ch)
                throw new Exception('failed to initialize');

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: text/html'
            ));

            $content = curl_exec($ch);

            if (FALSE === $content || NULL == $content)
                throw new Exception(curl_error($ch), curl_errno($ch));

           return $content;

        } catch(Exception $e) {

            trigger_error(sprintf(
                'Curl failed with error #%d: %s',
                $e->getCode(), $e->getMessage()),
                E_USER_ERROR);
        }

    }

    // Defining the XPATHObject
    public function returnXPathObject($item) {
        $xmlPageDom = new DomDocument();
        @$xmlPageDom->loadHTML($item);
        $xmlPageXPath = new DOMXPath($xmlPageDom);
        return $xmlPageXPath;
    }

    //Scraping page with xpath -> XPExpression: xpath expression
    public function returnData($XPExpression){
        $PageXpath = $this->returnXPathObject($this->packtPage);
        $data=$PageXpath->query($XPExpression);
        return $data;
    }

    public function useXPathOnDOMNode(DOMNode $element, $xPath){
        $xPathObj = $this->returnXPathObject($this->DOMinnerHTML($element));
        $data=$xPathObj->query($xPath);
        return $data;
    }

    function DOMinnerHTML(DOMNode $element)
    {
        $innerHTML = "";
        $children  = $element->childNodes;

        foreach ($children as $child)
        {
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }

        return $innerHTML;
    }

}