<?php

/*
 * This file is part of the Panda framework Ui component.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Panda\Ui\Frames;

use Panda\Ui\Contracts\Factories\HTMLFormFactoryInterface;
use Panda\Ui\DOMPrototype;
use Panda\Ui\Html\HTMLElement;
use Panda\Ui\Templates\Forms\Form;
use Panda\Ui\Templates\Forms\SimpleForm;

/**
 * Window Dialog Frame
 * Creates a dialog frame popup to display content to the user and perform an action.
 *
 * @package Panda\Ui\Frames
 */
class dialogFrame extends WindowFrame
{
    /**
     * OK/Cancel dialog buttons.
     *
     * @type    string
     */
    const TYPE_OK_CANCEL = "1";

    /**
     * Yes/No dialog buttons.
     *
     * @type    string
     */
    const TYPE_YES_NO = "2";

    /**
     * @type Form
     */
    private $form;

    /**
     * A formFactory object for building the form input objects.
     *
     * @type HTMLFormFactoryInterface
     */
    private $formFactory;

    /**
     * The form container that contains the form inputs.
     *
     * @type HTMLElement
     */
    private $formContent;

    /**
     * dialogFrame constructor.
     *
     * @param DOMPrototype             $HTMLDocument
     * @param HTMLFormFactoryInterface $FormFactory
     * @param string                   $id
     * @param string                   $class
     */
    public function __construct($HTMLDocument, $FormFactory, $id, $class)
    {
        parent::__construct($HTMLDocument, $id, $class);
        $this->formFactory = $FormFactory;
    }

    /**
     * Builds the frame along with the form action.
     *
     * @param    mixed   $title
     *        The dialog's title.
     *
     * @param    string  $action
     *        The form action to post the dialog to.
     *        Leave empty in order to engage with module or application protocol.
     *        It is empty by default.
     *
     * @param    boolean $background
     *        Defines whether the dialog popup will have a background.
     *        It is TRUE by default, as a dialog.
     *
     * @param    string  $type
     *        The dialog buttons type.
     *        Use class constants to define an OK/Cancel or Yes/No type.
     *        Default type is OK/Cancel.
     *
     * @return    dialogFrame
     *        The dialogFrame object.
     */
    public function build($title = "Dialog Frame", $action = "", $background = true, $type = self::TYPE_OK_CANCEL)
    {
        // Build window frame
        parent::build($title, "dialogFrame");

        // Build Form
        $this->form = new SimpleForm($this->getHTMLDocument(), $this->formFactory);
        $this->form->build(false, true);

        // Build form content
        $this->formContent = $this->getHTMLDocument()->create("div", "", "", "formContents");
        $this->form->appendToBody($this->formContent);

        // Build Controls
        $this->buildControls($type);

        return $this;
    }

    /**
     * Get the frame's form factory object.
     *
     * @return HTMLFormFactoryInterface
     */
    public function getFormFactory()
    {
        return $this->formFactory;
    }

    /**
     * Get the dialog's form id.
     *
     * @return string The form id.
     */
    public function getFormId()
    {
        return $this->form->attr("id");
    }

    /**
     * Builds the dialog controls.
     *
     * @param string $type The dialog buttons type.
     *                     Use class constants to define an OK/Cancel or Yes/No type.
     *                     Default type is OK/Cancel.
     */
    private function buildControls($type = self::TYPE_OK_CANCEL)
    {
        // Create dialog controls container
        $controlsContainer = $this->getHTMLDocument()->create("div", "", "", "dialogControls");
        $this->form->append($controlsContainer);

        // Button Container
        $btnContainer = $this->getHTMLDocument()->create("div", "", "", "ctrls");
        $controlsContainer->append($btnContainer);

        // Set button literals
        $lbl_submit = ($type == self::TYPE_OK_CANCEL ? "ok" : "yes");
        $lbl_reset = ($type == self::TYPE_OK_CANCEL ? "cancel" : "no");

        // Insert Controls
        $submitBtn = $this->formFactory->buildSubmitButton($lbl_submit, "", "", "dlgExec");
        $btnContainer->append($submitBtn);
        $resetBtn = $this->formFactory->buildResetButton($lbl_reset, "", "dlgCancel");
        $btnContainer->append($resetBtn);
    }
}