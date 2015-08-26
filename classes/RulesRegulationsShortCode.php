<?php
/**
 * Created by PhpStorm.
 * User: bryce
 * Date: 5/24/15
 * Time: 8:15 PM
 */

namespace RulesRegulations;


use RulesRegulations\Model\Rule;
use WordWrap\ShortCodeScriptLoader;
use WordWrap\View\View;
use WordWrap\View\ViewCollection;

class TimeLineShortCode extends ShortCodeScriptLoader{

    /**
     * @param  $atts array inputs
     * @return string shortcode content
     */
    public function handleShortcode($atts) {

        $rules = Rule::fetchAll();

        $collections = $this->buildCollections($rules);

        $exportedHTML = '';

        foreach($collections as $collection)
            $exportedHTML.= $collection->export();

        return $exportedHTML;
    }

    /**
     * @param $rules Rule[]
     * @return ViewCollection[]
     */
    private function buildCollections($rules) {
        $collections = [];

        foreach($rules as $rule) {
            $collection = new ViewCollection($this->lifeCycle, "front_end-rule");

            $collection->setTemplateVar("title", $rule->title);
            $collection->setTemplateVar("description", $rule->description);

            if(count($rule->children))
                $collection->addChildViews("children", $this->buildCollections($rule->children));

            $collections[] = $collection;
        }

        return $collections;
    }

    /**
     * Example:
     *   wp_register_script('my-script', plugins_url('js/my-script.js', __FILE__), array('jquery'), '1.0', true);
     *   wp_print_scripts('my-script');
     * @return void
     */
    public function addScript() {
        // TODO: Implement addScript() method.
    }
}