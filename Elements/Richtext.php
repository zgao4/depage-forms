<?php
/**
 * @file    richtext.php
 * @brief   richtext input element
 *
 * @author Frank Hellenkamp <jonas@depage.net>
 * @author Sebastian Reinhold <sebastian@bitbernd.de>
 **/

namespace Depage\HtmlForm\Elements;

/**
 * @brief HTML richtext element.
 *
 * adds a textarea-element with additional richtext-functionality
 * added by javascript
 *
 * Usage
 * -----
 *
 * @code
    <?php
        $form = new Depage\HtmlForm\HtmlForm('myform');

        // add a richtext field
        $form->addRichtext('html', array(
            'label' => 'Richtext box',
        ));

        // process form
        $form->process();

        // Display the form.
        echo ($form);
    @endcode
 **/
class Richtext extends Textarea
{
    // {{{ setDefaults()
    /**
     * @brief   collects initial values across subclasses
     *
     * The constructor loops through these and creates settable class
     * attributes at runtime. It's a compact mechanism for initialising
     * a lot of variables.
     *
     * @return void
     **/
    protected function setDefaults()
    {
        parent::setDefaults();

        $this->defaults['rows'] = null;
        $this->defaults['cols'] = null;
        $this->defaults['stylesheet'] = null;
        $this->defaults['autogrow'] = true;
        $this->defaults['allowedTags'] = array(
            // block elements
            "p",
            "br",
            "h1",
            "h2",
            "ul",
            "ol",
            "li",

            // inline elements
            "a",
            "b",
            "strong",
            "i",
            "em",
        );
    }
    // }}}
    // {{{ htmlWrapperAttributes()
    /**
     * @brief   Returns string of HTML attributes for element wrapper paragraph.
     *
     * @return string $attributes HTML attribute
     **/
    protected function htmlWrapperAttributes()
    {
        $attributes = parent::htmlWrapperAttributes();

        $options = array();
        $options['stylesheet'] = $this->stylesheet;
        $options['allowedTags'] = $this->allowedTags;

        $attributes .= " data-richtext-options=\"" . $this->htmlEscape(json_encode($options)) . "\"";

        return $attributes;
    }
    // }}}
    // {{{ htmlValue()
    /**
     * @brief   Returns HTML-rendered element value
     *
     * @return mixed element value
     **/
    protected function htmlValue()
    {
        if ($this->value === null) {
            $htmlDOM = $this->parseHtml($this->defaultValue);
        } else {
            $htmlDOM = $this->value;
        }

        $html = "";

        // add content of every node in body
        foreach ($htmlDOM->documentElement->childNodes as $node) {
            $html .= $htmlDOM->saveXML($node);
        }

        return $this->htmlEscape($html);
    }
    // }}}
    // {{{ typeCastValue()
    /**
     * @brief   Converts value into htmlDOM
     *
     * @return void
     **/
    protected function typeCastValue()
    {
        if (is_string($this->value)) {
            $this->value = $this->parseHtml($this->value);
        }
    }
    // }}}
    // {{{ parseHtml()
    /**
     * @brief   Parses html-string into htmlDOM
     *
     * @param string $html html string to parse
     *
     * @return Depage::HtmlForm::Abstract::HtmlDom htmlDOM
     **/
    protected function parseHtml($html)
    {
        $htmlDOM = new \Depage\HtmlForm\Abstracts\HtmlForm();

        $htmlDOM->loadHTML($html);
        $htmlDOM->cleanHTML($this->allowedTags);

        return $htmlDOM;
    }
    // }}}
}

/* vim:set ft=php sw=4 sts=4 fdm=marker et : */
