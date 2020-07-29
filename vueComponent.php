<?php

class vueComponent {

    var $component;

    function extractTemplate($script) {
        preg_match("/<template(.*?)>(.*?)<\/template>/ms", $script, $matches, PREG_OFFSET_CAPTURE, 0);
        $this->component->template = $matches[2][0];
        return preg_replace("/<template(.*?)>(.*?)<\/template>/ms", "", $script);
    }

    function extractScript($script) {
        preg_match("/<script(.*?)>(.*?)<\/script>/ms", $script, $matches, PREG_OFFSET_CAPTURE, 0);

        $js = $this->extractImport($matches[2][0]);

        $this->component->js = preg_replace("/.*export.*default.*{/m", "", $js);
    }
    
    function extractCSS($script) {
        preg_match("/<style(.*?)>(.*?)<\/style>/ms", $script, $matches, PREG_OFFSET_CAPTURE, 0);

        $js = $this->extractImport($matches[2][0]);

        $$this->component->template = $matches[2][0];
        return preg_replace("/<style(.*?)>(.*?)<\/style>/ms", "", $script);
    }

    function extractImport($script) {
        preg_match_all("/.*import.*from(.*)/m", $script, $matches, PREG_OFFSET_CAPTURE, 0);

        foreach ($matches[1] as $import) {
            $newComponent = new vueComponent();

            $newComponent->setComponent($import[0]);
            $this->component->components[] = $newComponent->getComponent();
        }

        return preg_replace("/.*import.*from(.*)/m", "", $script);
    }

    function setComponent($filename) {
        if (file_exists($filename)) {
            $script = file_get_contents($filename);
            $this->component = new stdClass();
            $this->component->isComponent = true;
            $this->component->name = pathinfo($filename, PATHINFO_FILENAME);;
            $script = $this->extractTemplate($script);
            $script = $this->extractScript($script);
            $script = $this->extractCSS($script);
        } else {
            $this->component = new stdClass();
            $this->component->isComponent = false;
        }
    }

    function getComponent() {
        return $this->component;
    }

    function ceateJS() {
        $this->component;

        $js = "/** VUE COMPONENTS */";
        if ($this->component->isComponent) {
            $js .= "\n Vue.component('{$component->name}',"
                    . "{"
                    . "template: `{$component->template}`,"
                    . "{$component->js}"
                    . ")";
        }
        
        $this->component->code = $js;
    }

}
