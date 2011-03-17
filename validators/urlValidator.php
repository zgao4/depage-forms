<?php
namespace depage\htmlform\validators;

class urlValidator extends regExValidator {
    public function __construct($log = null) {
        parent::__construct($log);

        $this->regEx = '/(((ht|f)tp(s?):\/\/)|(www\.[^ \[\]\(\)\n\r\t]+)|(([012]?[0-9]{1,2}\.){3}[012]?[0-9]{1,2})\/)([^ \[\]\(\),;&quot;\'&lt;&gt;\n\r\t]+)([^\. \[\]\(\),;&quot;\'&lt;&gt;\n\r\t])|(([012]?[0-9]{1,2}\.){3}[012]?[0-9]{1,2})/';
        $this->patternAttribute = "";
    }
}
