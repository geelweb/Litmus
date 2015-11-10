<?php
/**
 * Litmus Report object
 *
 * @package Litmus
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

require_once __DIR__ . '/RESTful/Client.php';

/**
 * @package Litmus
 */
class Litmus_Report
{
    /** @var int $id */
    public $id;
    /** @var \DateTime $created_at */
    public $created_at;
    /** @var string $report_name */
    public $report_name;
    /** @var string $bug_html */
    public $bug_html;
    /** @var bool|null $public_sharing */
    public $public_sharing;
    /** @var string|null $sharing_url */
    public $sharing_url;
    /** @var string|null $client_usage */
    public $client_usage;
    /** @var string|null $client_engagement */
    public $client_engagement;
    /** @var string|null $activity */
    public $activity;


    /**
     * Implements the reports and reports/show methods to get
     * all or one report. Returns an array of Litmus_Report object or a single
     * Litmus_Report object if a report_id is provided
     *
     * @param integer $report_id Id of the report to retrieve
     * @return Litmus_Report[]|Litmus_Report
     */
    public static function getReports($report_id = null)
    {
        $rc = Litmus_RESTful_Client::singleton();
        if ($report_id === null) {
            $res = $rc->get('reports.xml');
        } else {
            $res = $rc->get('reports/' . $report_id . '.xml');
        }

        $tests = Litmus_Report::load($res);
        if ($report_id !== null) {
            $tests = array_pop($tests);
        }
        return $tests;
    }

    /**
     * Implements the reports/create method to create a new report
     *
     * @param string $name The name of the report
     * @return Litmus_Report
     */
    public static function create($name)
    {
        $dom = new DomDocument('1.0', null);
        $root = $dom->createElement('report');
        $dom->appendChild($root);

        $nameElement = $dom->createElement('name', $name);
        $root->appendChild($nameElement);

        $request = $dom->saveXML();

        $rc = Litmus_RESTful_Client::singleton();
        $res = $rc->post('reports.xml', $request);

        $tests = Litmus_Report::load($res);
        return array_pop($tests);
    }

    /**
     * Load a Litmus_Report object or collection from an xml content.
     *
     * @param string $xml XML content
     * @return mixed
     */
    private static function load($xml)
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $lst = $dom->getElementsByTagName('report');
        $col = array();
        foreach ($lst as $item) {
            $obj = new Litmus_Report();
            /** @var \DOMNode $child */
            foreach ($item->childNodes as $child) {
                $property = $child->nodeName;
                $value = $child->nodeValue;

                if ($child->nodeName === '#text') {
                    // Ignore text node
                    continue;
                }

                $dataType = null;
                if ($child->hasAttributes()) {
                    $dataType = strtolower($child->attributes->getNamedItem('type')->nodeValue);
                }

                switch ($dataType) {
                    case 'datetime':
                        $value = new \DateTime($value);
                        break;
                    case 'integer':
                        $value = (int)$value;
                        break;
                    case 'boolean':
                        $value = ($value === 'true' ? true : false);
                        break;
                }

                // Handle inconsistency between `reports` and `report/{id}.xml`
                if ($property === 'name') {
                    $property = 'report_name';
                }

                // Save to Report object
                $obj->{$property} = $value;
            }
            array_push($col, $obj);
        }
        return $col;
    }
}
